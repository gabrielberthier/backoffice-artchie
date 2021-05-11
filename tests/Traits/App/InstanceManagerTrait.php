<?php

namespace Tests\Traits\App;

use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\HTTP\HTTPRequestFactory;
use Psr\Container\ContainerInterface;
use Slim\App;

trait InstanceManagerTrait
{
    /**
     * @throws Exception
     */
    final protected function getAppInstance(): App
    {
        $appBuilder = new AppBuilderManager($this->setUpContainer());
        $request = new HTTPRequestFactory();

        return $appBuilder->build($request->createRequest());
    }

    final protected function setUpContainer(): ContainerInterface
    {
        $containerFactory = new ContainerFactory();

        $containerFactory
            // Set to true in production
            ->enableCompilation(false)
            // Make use of annotations in classes
            ->withAnnotations()
        ;

        $this->container = $containerFactory->get();

        return $this->container;
    }
}
