<?php

namespace Tests\Traits\App;

use App\Presentation\Handlers\HttpErrorHandler;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

trait ErrorHandlerTrait
{
    protected function setUpErrorHandler(App $app)
    {
        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);
    }
}
