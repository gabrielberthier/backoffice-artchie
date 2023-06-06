<?php

namespace Core\Builder;

use Core\Providers\AppProviderInterface;
use Core\ResourceLoader;
use DI\ContainerBuilder;

class ProvidersCollector
{
    /**
     * @var AppProviderInterface[]
     */
    private array $collector = [];

    public function __construct(private ContainerBuilder $containerBuilder)
    {
        /**
         * @var array
         */
        $providers = ResourceLoader::getResource('providers');
        foreach ($providers as $provider) {
            $entity = new $provider();
            $this->push($entity);
        }
    }

    public function push(AppProviderInterface $provider)
    {
        $this->collector[] = $provider;
    }

    public function roll(): ContainerBuilder
    {
        foreach ($this->collector as $provider) {
            $resource = ResourceLoader::getResource($provider->getTarget());
            $provider->provide($this->containerBuilder, $resource);
        }

        return $this->containerBuilder;
    }
}