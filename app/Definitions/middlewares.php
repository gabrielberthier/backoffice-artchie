<?php

use App\Presentation\Middleware\JWTAuthMiddleware;
use App\Presentation\Middleware\SessionMiddleware;
// use Core\Http\Middlewares\DatabaseKeepAliveMiddleware;
use Slim\Middleware\BodyParsingMiddleware;

return [
    SessionMiddleware::class,
    JWTAuthMiddleware::class,
    BodyParsingMiddleware::class,
    // DatabaseKeepAliveMiddleware::class
    //ErrorMiddleware::class
];