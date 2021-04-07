<?php

namespace App\Presentation\Handlers;

use App\Infrastructure\Cryptography\BodyTokenCreator;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class OnJwtErrorHandler
{
    public function __construct(private string $refreshToken)
    {
    }

    public function __invoke(ResponseInterface $response, $arguments): ResponseInterface
    {
        $data['status'] = 'ok';
        $data['message'] = 'token refreshed';

        try {
            $object = JWT::decode($this->refreshToken, $this->secret, ['HS256']);
            $uuid = $object->data->uuid;
            $user = $repository->findAccountByID($uuid);
            $tokenCreator = new BodyTokenCreator($user);

            $secretBody = getenv('JWT_SECRET') ?? 'any_secret';
            $data['token'] = $tokenCreator->createToken($secretBody);

            return $this->appendToBody($response, 401, $data);
        } catch (Throwable) {
            $data['status'] = 'error';
            $data['message'] = $arguments['message'];

            return $this->appendToBody($response, 401, $data);
        }
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
