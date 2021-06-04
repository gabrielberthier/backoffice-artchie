<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Infrastructure\Cryptography\Exceptions\AppHasNoDefinedSecrets;
use App\Presentation\Handlers\RefreshTokenHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Tuupola\Middleware\JwtAuthentication;

class JWTAuthMiddleware implements Middleware
{
    public function __construct(
        private LoggerInterface $logger,
        private RefreshTokenHandler $refreshTokenHandler
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->grantHasSecrets();

        $authMiddleware = $this->boot();

        $this->interceptRefreshToken($request);

        return $authMiddleware->process($request, $handler);
    }

    private function grantHasSecrets()
    {
        $shouldHave = ['JWT_SECRET', 'JWT_SECRET_COOKIE'];
        foreach ($shouldHave as $field) {
            if (!array_key_exists($field, $_ENV)) {
                throw new AppHasNoDefinedSecrets($field);
            }
        }
    }

    private function boot(): JwtAuthentication
    {
        $secret = $_ENV['JWT_SECRET'];
        $shouldBeSecure = 'PRODUCTION' === $_ENV['MODE'];

        return new JwtAuthentication([
            'secret' => $secret,
            'path' => '/api',
            'ignore' => ['/api/auth', '/admin/ping'],
            'logger' => $this->logger,
            'error' => $this->refreshTokenHandler,
            'relaxed' => ['localhost', 'dev.example.com'],
            'secure' => $shouldBeSecure,
        ]);
    }

    private function interceptRefreshToken(ServerRequestInterface $request): void
    {
        $cookies = $request->getCookieParams();
        $cookieName = REFRESH_TOKEN;
        $refreshToken = $cookies[$cookieName] ?? null;
        if (is_string($refreshToken)) {
            $request = $request->withAttribute($cookieName, $refreshToken);
            $this->refreshTokenHandler->setRefreshToken($refreshToken);
        }
    }
}
