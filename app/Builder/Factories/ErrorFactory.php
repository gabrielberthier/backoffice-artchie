<?php

namespace Core\Builder\Factories;

use App\Presentation\Handlers\HttpErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\ErrorHandlerInterface;

class ErrorFactory
{
    private ErrorHandlerInterface $errorHandler;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function createErrorHandler(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory): ErrorHandlerInterface
    {
        $logger = $this->container->get(LoggerInterface::class);

        $this->errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $logger);

        return $this->errorHandler;
    }
}
