<?php

namespace Tests\Traits\App;

use Slim\App;
use Slim\Factory\AppFactory;

trait InstanceManagerTrait
{
    /**
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        $container = require __DIR__ . '/../../../configs/container-setup.php';

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../../../app/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../../../app/routes.php';
        $routes($app);

        return $app;
    }
}
