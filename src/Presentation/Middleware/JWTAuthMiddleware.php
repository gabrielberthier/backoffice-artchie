<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Presentation\Handlers\OnJwtErrorHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Tuupola\Middleware\JwtAuthentication;

class JWTAuthMiddleware implements Middleware
{
    public function __construct(private LoggerInterface $logger, private OnJwtErrorHandler $errorHandler)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authMiddleware = $this->boot();

        $this->interceptRefreshToken($request);

        return $authMiddleware->process($request, $handler);
    }

    private function boot(): JwtAuthentication
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'ANY_HASH';

        return new JwtAuthentication([
            'secret' => $secret,
            'path' => '/api',
            'ignore' => ['/api/auth', '/admin/ping'],
            'logger' => $this->logger,
            'error' => $this->errorHandler,
            'relaxed' => ['localhost', 'dev.example.com'],
        ]);
    }

    private function destroyJWT(): void
    {
        $key = 'refresh_token';
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600, '/'); // empty value and old timestamp
    }

    private function interceptRefreshToken(ServerRequestInterface $request): void
    {
        $cookies = $request->getCookieParams();
        $refreshToken = $cookies['refresh_token'] ?? null;
        if (is_string($refreshToken)) {
            $request = $request->withAttribute('refresh-token', $refreshToken);
            $this->errorHandler->setRefreshToken($refreshToken);
        }
    }
}
