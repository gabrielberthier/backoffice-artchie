<?php

namespace Core\Builder\Factories;

use Core\Builder\ProvidersCollector;
use DI\Container;
use DI\ContainerBuilder;

class ContainerFactory
{
    private ContainerBuilder $containerBuilder;
    private bool $useAnnotations = false;

    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
    }

    public function get(): Container
    {
        $containerBuilder = $this->setContainerValues();
        $containerBuilder->useAnnotations($this->useAnnotations);

        return $containerBuilder->build();
    }

    public function enableCompilation(bool $enable): self
    {
        if ($enable) { // Should be set to true in production
            $this->containerBuilder->enableCompilation(__DIR__.'/../var/cache');
        }

        return $this;
    }

    public function withAnnotations(): self
    {
        $this->useAnnotations = true;

        return $this;
    }

    public function withoutAnnotations(): self
    {
        $this->useAnnotations = false;

        return $this;
    }

    private function setContainerValues(): ContainerBuilder
    {
        $providersCollector = new ProvidersCollector($this->containerBuilder);

        return $providersCollector->roll();
    }
}
