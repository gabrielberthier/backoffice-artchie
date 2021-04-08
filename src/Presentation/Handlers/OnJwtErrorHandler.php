<?php

namespace App\Presentation\Handlers;

use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class OnJwtErrorHandler
{
    private string $refreshToken;

    public function __construct(private AccountRepository $repository)
    {
    }

    public function __invoke(ResponseInterface $response, $arguments): ResponseInterface
    {
        $data['status'] = 'ok';
        $data['message'] = 'token refreshed';
        $secretBody = getenv('JWT_SECRET') ?? 'any_secret';
        $secretToken = getenv('JWT_SECRET_COOKIE') ?? 'any_secret';

        try {
            $object = JWT::decode($this->refreshToken, $secretToken, ['HS256']);
            $uuid = $object->data->uuid;
            $user = $this->repository->findByUUID($uuid);
            $tokenCreator = new BodyTokenCreator($user);

            $data['token'] = $tokenCreator->createToken($secretBody);

            return $this->appendToBody($response, 401, $data);
        } catch (Throwable) {
            $data['status'] = 'error';
            $data['message'] = $arguments['message'];

            return $this->appendToBody($response, 401, $data);
        }
    }

    public function setRefreshToken(string $token)
    {
        $this->refreshToken = $token;
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
