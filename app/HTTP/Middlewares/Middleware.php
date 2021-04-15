<?php

declare(strict_types=1);

namespace Core\HTTP\Middlewares;

use Core\ResourceLoader;
use Slim\App;

class Middleware
{
    public static function run(App $app)
    {
        // Defaults
        $app->addBodyParsingMiddleware(); // Add parser that handles body values
        $app->addRoutingMiddleware(); // Add the Slim built-in routing middleware

        // Apply middlewares
        $definitions = ResourceLoader::getResource('middlewares');
        foreach ($definitions as $middleware) {
            $app->add($middleware);
        }
    }
}
