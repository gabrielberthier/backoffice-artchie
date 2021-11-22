<?php

declare(strict_types=1);

namespace Core\HTTP\Middlewares;

use Core\ResourceLoader;
use Middlewares\TrailingSlash;
use Slim\App;

class Middleware
{
    public function run(App $app)
    {
        // Defaults
        $app->addBodyParsingMiddleware(); // Add parser that handles body values
        $app->addRoutingMiddleware(); // Add the Slim built-in routing middleware
        $app->add(new TrailingSlash());

        // Apply middlewares
        $definitions = ResourceLoader::getResource('middlewares');
        foreach ($definitions as $middlewareClass) {
            $app->add($middlewareClass);
        }
    }
}
