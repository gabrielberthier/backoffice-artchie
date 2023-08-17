<?php

namespace App\Presentation\Factories;

use App\Domain\Repositories\AccountRepository;
use App\Presentation\Handlers\RefreshTokenHandler;
use App\Presentation\Helpers\Interceptors\RefreshTokenInterceptor;
use Psr\Http\Message\ServerRequestInterface;

class RefreshTokenHandlerFactory
{
    public function __construct(
        private AccountRepository $repository,
        private RefreshTokenInterceptor $interceptor
    ) {
    }

    public function create(ServerRequestInterface $request): ?RefreshTokenHandler
    {
        $secretBody = $_ENV['JWT_SECRET'];
        $secretToken = $_ENV['JWT_SECRET_COOKIE'];

        $refreshToken = $this->interceptor->interceptRefreshToken($request);

        return new RefreshTokenHandler($this->repository, $refreshToken, $secretBody, $secretToken);
    }
}