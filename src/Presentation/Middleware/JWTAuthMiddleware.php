<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Infrastructure\Cryptography\Exceptions\AppHasNoDefinedSecrets;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Core\Http\Middlewares\Jwt\JwtAuthentication;
use Psr\Log\LoggerInterface;

class JWTAuthMiddleware implements Middleware
{
    public function __construct(private LoggerInterface $logger)
    {
        $shouldHave = ["JWT_SECRET", "JWT_SECRET_COOKIE"];

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
        $secret = $_ENV["JWT_SECRET"];

        $options = [
            "secret" => $secret,
            "path" => "/api",
            "ignore" => ["/api/auth", "/admin/ping"],
            // 'before' => $beforeFunction,
            "logger" => $this->logger,
            "relaxed" => ["localhost", "dev.example.com"],
            "secure" => false,
            "error" => function (Response $response, array $args): Response {
                $response = $response->withHeader('Content-Type', 'application/json');

                $response
                    ->getBody()
                    ->write(
                        json_encode([
                            "message" =>
                            "You are not allowed to acess this resource",
                        ])
                    );

                return $response;
            }
        ];

        $jwtAuth = new JwtAuthentication($options);

        return $jwtAuth->process($request, $handler);
    }
}
