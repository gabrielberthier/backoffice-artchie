<?php

namespace Core\Builder;

use App\Presentation\Handlers\HttpErrorHandler;
use Core\Builder\Factories\ContainerFactory;
use Core\Builder\Factories\ErrorFactory;
use Core\Builder\Factories\ShutdownHandlerFactory;
use Core\HTTP\Middlewares\Middleware;
use Core\HTTP\Routing\Router;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;

class AppBuilderManager
{
    private ContainerInterface $container;

    private ErrorFactory $errorFactory;

    private bool $displayErrors;

    private bool $enableErrorHandler = true;

    private bool $enableShutdownHandler = true;

    public function __construct(private ContainerFactory $containerFactory)
    {
        $this->container = $this
            ->containerFactory
            // Set to true in production
            ->enableCompilation(false)
            // Make use of annotations in classes
            ->withAnnotations()
            ->get()
        ;

        $this->displayErrors = $this->container->get('settings')['displayErrorDetails'];

        $this->errorFactory = new ErrorFactory($this->container);
    }

    public function build(ServerRequestInterface $request): App
    {
        $app = $this->createApp();

        Middleware::run($app);
        Router::run($app);

        $app->addRoutingMiddleware();

        if ($this->enableErrorHandler) {
            $this->setErrorHandler($app, $request);
        }

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

    private function applyShutdownHandler(ServerRequestInterface $request, HttpErrorHandler $httpErrorHandler)
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
