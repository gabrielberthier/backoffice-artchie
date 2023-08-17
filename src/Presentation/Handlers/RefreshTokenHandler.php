<?php

namespace App\Presentation\Handlers;

use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * This class is called once the validation of a user's JWT throws invalid.
 * Then, this class instance is responsible to verify the user's refresh token existence and
 * forge a new JWT in case the Refresh Token exists.
 */
class RefreshTokenHandler
{
    public function __construct(
        private AccountRepository $repository,
        private string $refreshToken,
        private string $secretBody,
        private string $secretToken,
    ) {
    }

    public function __invoke(ResponseInterface $response, array $arguments = []): ResponseInterface
    {
        $statusCode = 201;

        try {
            $key = new Key(
                $this->secretToken,
                'HS256'
            );
            $payload = JWT::decode($this->refreshToken, $key);
            $token = $this->createRenewToken($payload);

            $response = $response->withHeader('X-RENEW-TOKEN', $token);
        } catch (Throwable) {
            $statusCode = 401;
            $status = 'error';
            $message = 'You are not logged to access this resource';
            $data = ['status' => $status, 'message' => $message];

            $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }

        return $response->withStatus($statusCode)->withHeader('Content-Type', 'application/json');
    }

    private function createRenewToken(object $payload): string
    {
        $uuid = $payload->sub;
        $user = $this->repository->findByUUID($uuid);
        $tokenCreator = new BodyTokenCreator($user);

        return $tokenCreator->createToken($this->secretBody);
    }
}