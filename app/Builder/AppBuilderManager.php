<?php

namespace Core\Builder;

use Core\Builder\Factories\ErrorFactory;
use Core\Builder\Factories\ShutdownHandlerFactory;
use Core\Http\Adapters\SlimFramework\SlimMiddlewareIncluder;
use Core\Http\Adapters\SlimFramework\SlimRouteCollector;
use Core\Http\RouterCollector;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Respect\Validation\Factory;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\ErrorHandlerInterface;


class AppBuilderManager
{
    private ContainerInterface $container;

    private ErrorFactory $errorFactory;

    private bool $displayErrors;

    private bool $enableErrorHandler = true;

    private bool $enableShutdownHandler = true;

    /** @var MiddlewareInterface[] */
    private array $preMiddlewares = [];

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;

        $this->displayErrors = $this->container->get('settings')['displayErrorDetails'];

        $this->errorFactory = new ErrorFactory($this->container);
    }

    public function appendMiddlewares(MiddlewareInterface $middlewareInterface){
        $this->preMiddlewares[] = $middlewareInterface;
    }

    public function build(ServerRequestInterface $request): App
    {
        $app = $this->createApp();

        $app->addRoutingMiddleware(); // Add the Slim built-in routing middleware

        foreach ($this->preMiddlewares as $preMiddleware) {
            $app->addMiddleware($preMiddleware);
        }

        \Core\Builder\MiddlewareCollector::collect(new SlimMiddlewareIncluder($app));

        $router = new RouterCollector(new SlimRouteCollector($app));

        $router->run();

        if ($this->enableErrorHandler) {
            $this->setErrorHandler($app, $request);
        }

        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace('App\\Presentation\\Helpers\\Validation\\Rules')
                ->withExceptionNamespace('App\\Presentation\\Helpers\\Validation\\Exceptions')
        );

        return $app;
    }

    public function useDefaultErrorHandler(bool $enable)
    {
        $this->enableErrorHandler = $enable;
    }

    public function useDefaultShutdownHandler(bool $enable)
    {
        if (!$this->enableErrorHandler) {
            throw new Exception('Unable to use default shutdown handler when error handler is not enabled');
        }
        $this->enableShutdownHandler = $enable;
    }

    private function setErrorHandler(App $app, ServerRequestInterface $request)
    {
        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();
        $errorHandler = $this->errorFactory->createErrorHandler($callableResolver, $responseFactory);
        if ($this->enableShutdownHandler) {
            $this->applyShutdownHandler($request, $errorHandler);
        }
        $errorMiddleware = $app->addErrorMiddleware($this->displayErrors, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    private function applyShutdownHandler(ServerRequestInterface $request, ErrorHandlerInterface $httpErrorHandler)
    {
        $shutdownHandler = new ShutdownHandlerFactory(
            $request,
            $httpErrorHandler
        );
        $shutdownHandler->setShutdownHandler($this->displayErrors);
    }

    // Instantiate the app
    private function createApp(): App
    {
        AppFactory::setContainer($this->container);

        return AppFactory::create();
    }
}