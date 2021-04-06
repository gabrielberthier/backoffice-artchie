<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use Psr\Http\Message\ResponseInterface;
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

        return $authMiddleware->process($request, $handler);
    }

    private function boot(): JwtAuthentication
    {
        /*
        * Options:
        *   "secure" => true,
        *   "relaxed" => ["localhost", "127.0.0.1"],
        *   "algorithm" => ["HS256", "HS512", "HS384"],
        *   "header" => "Authorization",
        *   "regexp" => "/Bearer\s+(.*)$/i",
        *   "cookie" => "token",
        *   "attribute" => "token",
        *   "path" => "/",
        *   "ignore" => null,
        *   "before" => null,
        *   "after" => null,
        *   "error" => null
        */
        return new JwtAuthentication([
            'secret' => $this->secret,
            'path' => '/api',
            'ignore' => ['/api/auth', '/admin/ping'],
            'logger' => $this->logger,
            'before' => function (ServerRequestInterface $request, $arguments) {
                /**
                 * @todo Check here to verify httpOnly refreshToken
                 */
                $cookies = $request->getCookieParams();
                $refreshToken = $cookies['refresh_token'] ?? null;
                if ($refreshToken) {
                    $arguments['refresh_token'] = $refreshToken;
                    $request = $request->withAttribute('refresh', $refreshToken);
                }

                return $request;
            },
            'error' => function (?ResponseInterface $response, $arguments) {
                $data['status'] = 'error';
                $data['message'] = $arguments['message'];

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401)
                    ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            },
            'relaxed' => ['localhost', 'dev.example.com'],
        ]);
    }

    private function destroyJWT()
    {
        $key = 'refresh_token';
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600, '/'); // empty value and old timestamp
    }
}
