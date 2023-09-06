<?php

namespace Tests\Traits\App;

use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\Http\Factories\RequestFactory;
use Psr\Container\ContainerInterface;
use Slim\App;

trait InstanceManagerTrait
{
    protected static ?ContainerInterface $container = null;
    protected App $app;

    /**
     * @throws \Exception
     */
    final protected function getAppInstance(): App
    {
        $appBuilder = new AppBuilderManager($this->getContainer());
        $request = new RequestFactory();

        return $appBuilder->build($request->createRequest());
    }

    final protected function createAppInstance()
    {
        $appBuilder = new AppBuilderManager($this->getContainer(true));
        $request = new RequestFactory();

        return $appBuilder->build($request->createRequest());
    }

    protected function getContainer(bool $forceUpdate = false): ContainerInterface
    {
        return self::requireContainer($forceUpdate);
    }

    protected function autowireContainer($key, $instance)
    {
        /**
         * @var ContainerInterface
         */
        $container = $this->getContainer();
        $container->set($key, $instance);
    }

    static function setUpContainer(): ContainerInterface
    {
        $containerFactory = new ContainerFactory();

        $containerFactory
            ->enableCompilation(false)
        ;

        return $containerFactory->get();
    }

    static function requireContainer(bool $forceUpdate = false): ContainerInterface
    {
        if (null === self::$container || $forceUpdate) {
            self::$container = self::setUpContainer();
        }

        return self::$container;
    }
}