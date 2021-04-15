<?php

namespace Tests\Traits\App;

use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\HTTP\HTTPRequestFactory;
use Slim\App;

trait InstanceManagerTrait
{
    /**
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        $containerFactory = new ContainerFactory();

        $appBuilder = new AppBuilderManager($containerFactory);
        $request = new HTTPRequestFactory();

        return $appBuilder->build($request->createRequest());
    }
}
