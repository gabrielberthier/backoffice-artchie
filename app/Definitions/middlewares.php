<?php

use App\Presentation\Middleware\JWTAuthMiddleware;
use App\Presentation\Middleware\SessionMiddleware;

return [
    SessionMiddleware::class,
    JWTAuthMiddleware::class,
    //ErrorMiddleware::class
];
