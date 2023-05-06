<?php

namespace Tests\Traits\App;

use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\Http\HTTPRequestFactory;
use Psr\Container\ContainerInterface;
use Slim\App;

trait InstanceManagerTrait
{
    protected static ?ContainerInterface $container = null;
    protected App $app;

    /**
     * @throws Exception
     */
    final protected function getAppInstance(): App
    {
        $appBuilder = new AppBuilderManager($this->getContainer());
        $request = new HTTPRequestFactory();

        return $appBuilder->build($request->createRequest());
    }

    final protected function createAppInstance()
    {
        $appBuilder = new AppBuilderManager($this->getContainer(true));
        $request = new HTTPRequestFactory();

        return $appBuilder->build($request->createRequest());
    }

    protected function getContainer(bool $forceUpdate = false): ContainerInterface
    {
        if (null === self::$container || $forceUpdate) {
            self::$container = $this->setUpContainer();
        }

        return self::$container;
    }

    protected function autowireContainer($key, $instance)
    {
        /**
         * @var Container
         */
        $container = $this->getContainer();
        $container->set($key, $instance);
    }

    private function setUpContainer(): ContainerInterface
    {
        $containerFactory = new ContainerFactory();

        $containerFactory
            // Set to true in production
            ->enableCompilation(false)
            // Make use of annotations in classes
            ->withAnnotations()
        ;

        return $containerFactory->get();
    }
}
