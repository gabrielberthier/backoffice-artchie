<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Infrastructure\Cryptography\Exceptions\AppHasNoDefinedSecrets;
use App\Presentation\Factories\RefreshTokenHandlerFactory;
use App\Presentation\Handlers\RefreshTokenHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpForbiddenException;
use Tuupola\Middleware\JwtAuthentication;

class JWTAuthMiddleware implements Middleware
{
    public function __construct(
        private LoggerInterface $logger,
        private RefreshTokenHandlerFactory $refreshTokenHandlerFactory
    ) {
        $shouldHave = ['JWT_SECRET', 'JWT_SECRET_COOKIE'];

        foreach ($shouldHave as $field) {
            if (!array_key_exists($field, $_ENV)) {
                throw new AppHasNoDefinedSecrets($field);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $refreshTokenHandler = $this->refreshTokenHandlerFactory->create($request);

        $authMiddleware = $this->boot($refreshTokenHandler);

        return $authMiddleware->process($request, $handler);
    }

    private function boot(?RefreshTokenHandler $refreshTokenHandler): JwtAuthentication
    {
        $secret = $_ENV['JWT_SECRET'];
        $shouldBeSecure = 'PRODUCTION' === $_ENV['MODE'];

        // $beforeFunction = function (Request $request, $arguments) use ($refreshTokenHandler) {
        //     if (!$refreshTokenHandler) {
        //         throw new HttpForbiddenException($request, 'Insufficient privileges');
        //     }

        //     return $request;
        // };

        return new JwtAuthentication([
            'secret' => $secret,
            'path' => '/api',
            'ignore' => ['/api/auth', '/admin/ping'],
            // 'before' => $beforeFunction,
            'logger' => $this->logger,
            'error' => $refreshTokenHandler,
            'relaxed' => ['localhost', 'dev.example.com'],
            'secure' => $shouldBeSecure,
        ]);
    }
}
