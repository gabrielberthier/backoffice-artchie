<?php

namespace Core\Builder\Factories;

use App\Presentation\Handlers\HttpErrorHandler;
use App\Presentation\Handlers\ShutdownHandler;
use Psr\Http\Message\ServerRequestInterface;

class ShutdownHandlerFactory
{
    public function __construct(private ServerRequestInterface $request, private HttpErrorHandler $httpErrorHandler)
    {
    }

    public function setShutdownHandler(bool $displayErrorDetails)
    {
        $shutdownHandler = new ShutdownHandler($this->request, $this->httpErrorHandler, $displayErrorDetails);
        register_shutdown_function($shutdownHandler);
    }
}
