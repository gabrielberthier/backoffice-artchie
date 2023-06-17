<?php

use App\Presentation\Middleware\JWTAuthMiddleware;
use App\Presentation\Middleware\SessionMiddleware;
use Slim\Middleware\BodyParsingMiddleware;

return [
    SessionMiddleware::class,
    JWTAuthMiddleware::class,
    BodyParsingMiddleware::class,
    //ErrorMiddleware::class
];