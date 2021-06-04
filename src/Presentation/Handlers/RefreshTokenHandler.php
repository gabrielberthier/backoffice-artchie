<?php

namespace App\Presentation\Handlers;

use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @docs This class is called once the validation of a user's JWT throws invalid.
 * Then, this class instance is responsible to verify the user's refresh token existence and
 * forge a new JWT in case the Refresh Token exists.
 */
class RefreshTokenHandler
{
    private string $refreshToken = '';
    private string $secretBody;
    private string $secretToken;

    public function __construct(private AccountRepository $repository)
    {
        $this->secretBody = $_ENV['JWT_SECRET'];
        $this->secretToken = $_ENV['JWT_SECRET_COOKIE'];
    }

    public function __invoke(ResponseInterface $response, $arguments): ResponseInterface
    {
        $status = 'ok';
        $message = 'token refreshed';
        $statusCode = 201;
        $data = [];

        try {
            $payload = JWT::decode($this->refreshToken, $this->secretToken, ['HS256']);
            $data['token'] = $this->createBodyToken($payload);
        } catch (Throwable $ex) {
            $status = 'error';
            $message = $arguments['message'];
            $statusCode = 401;
        }

        $data['status'] = $status;
        $data['message'] = $message;

        return $this->appendToBody($response, $statusCode, $data);
    }

    public function setRefreshToken(string $token)
    {
        $this->refreshToken = $token;
    }

    private function createBodyToken(object $payload): string
    {
        $uuid = $payload->sub;
        $user = $this->repository->findByUUID($uuid);
        $tokenCreator = new BodyTokenCreator($user);

        return $tokenCreator->createToken($this->secretBody);
    }

    private function appendToBody(ResponseInterface $response, int $status, array $data): ResponseInterface
    {
        $adaptedResponse = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status)
        ;
        $adaptedResponse->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        return $adaptedResponse;
    }
}
