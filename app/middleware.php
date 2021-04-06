<?php

declare(strict_types=1);

use App\Presentation\Middleware\JWTAuthMiddleware;
use App\Presentation\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    $app->add(JWTAuthMiddleware::class);

    // Catch exceptions and errors
    // $app->add(ErrorMiddleware::class);
};
