<?php

namespace Core\Builder;

use App\Presentation\Handlers\ShutdownHandler;
use Core\Builder\MiddlewareCollector;
use Core\Http\Adapters\SlimFramework\SlimMiddlewareIncluder;
use Core\Http\Adapters\SlimFramework\SlimRouteCollector;
use Core\Http\RouterCollector;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;
use Respect\Validation\Factory;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\ErrorHandlerInterface;
use App\Presentation\Handlers\HttpErrorHandler;


class AppBuilderManager
{
    private bool $displayErrors;
    public function __construct(
        private ContainerInterface $container,
        private bool $enableErrorHandler = true,
        private bool $enableShutdownHandler = true,
        private array $preMiddlewares = []
    ) {
        $this->displayErrors = $this->container->get('settings')['displayErrorDetails'];
    }

    public function appendMiddlewares(MiddlewareInterface $middlewareInterface)
    {
        $this->preMiddlewares[] = $middlewareInterface;
    }

    public function build(ServerRequestInterface $request): App
    {
        $app = $this->createApp();

        $app->addRoutingMiddleware(); // Add the Slim built-in routing middleware

        foreach ($this->preMiddlewares as $preMiddleware) {
            $app->addMiddleware($preMiddleware);
        }

        MiddlewareCollector::collect(new SlimMiddlewareIncluder($app));

        $router = new RouterCollector(new SlimRouteCollector($app));

        $router->run();

        if ($this->enableErrorHandler) {
            $this->setErrorHandler($app, $request);
        }

        $this->setCustomValidations();

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

    private function setCustomValidations()
    {
        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace('App\\Presentation\\Helpers\\Validation\\Rules')
                ->withExceptionNamespace('App\\Presentation\\Helpers\\Validation\\Exceptions')
        );
    }

    private function setErrorHandler(App $app, ServerRequestInterface $request)
    {
        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $logger = $this->container->get(LoggerInterface::class);

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $logger);

        if ($this->enableShutdownHandler) {
            $this->applyShutdownHandler($request, $errorHandler);
        }

        $errorMiddleware = $app->addErrorMiddleware($this->displayErrors, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    private function applyShutdownHandler(ServerRequestInterface $request, ErrorHandlerInterface $httpErrorHandler)
    {
        $shutdownHandler = new ShutdownHandler($request, $httpErrorHandler, $this->displayErrors);

        register_shutdown_function($shutdownHandler);
    }

    // Instantiate the app
    private function createApp(): App
    {
        AppFactory::setContainer($this->container);

        return AppFactory::create();
    }
}