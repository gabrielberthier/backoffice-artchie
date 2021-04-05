<?php

declare(strict_types=1);

use App\Presentation\Middleware\SessionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    /*
     *
     * "secure" => true,
     * "relaxed" => ["localhost", "127.0.0.1"],
     * "algorithm" => ["HS256", "HS512", "HS384"],
     * "header" => "Authorization",
     * "regexp" => "/Bearer\s+(.*)$/i",
     * "cookie" => "token",
     * "attribute" => "token",
     * "path" => "/",
     * "ignore" => null,
     * "before" => null,
     * "after" => null,
     * "error" => null
     */
    $app->add(new JwtAuthentication([
        'secret' => getenv('JWT_SECRET'),
        'path' => '/api',
        'ignore' => ['/api/auth', '/admin/ping'],
        'logger' => $app->getContainer()->get('logger'),
        'before' => function ($request, $arguments) {
            return $request->withAttribute('test', 'test');
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
    ]));

    // Catch exceptions and errors
    // $app->add(ErrorMiddleware::class);
};
