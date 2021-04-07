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
    private string $secret;
    private ?string $refreshToken = null;
    private OnJwtErrorHandler $errorHandler;

    public function __construct(private LoggerInterface $logger)
    {
        $this->secret = $_ENV['JWT_SECRET'] ?? 'ANY_HASH';
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authMiddleware = $this->boot();

        $this->verifyRefreshToken($request);

        return $authMiddleware->process($request, $handler);
    }

    private function boot(): JwtAuthentication
    {
        return new JwtAuthentication([
            'secret' => $this->secret,
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

    private function verifyRefreshToken(ServerRequestInterface $request): void
    {
        $cookies = $request->getCookieParams();
        $refreshToken = $cookies['refresh_token'] ?? null;
        if ($refreshToken) {
            $this->refreshToken = $refreshToken;
            $request = $request->withAttribute('refresh', $this->refreshToken);
        }
        $this->errorHandler = new OnJwtErrorHandler($refreshToken);
    }
}
