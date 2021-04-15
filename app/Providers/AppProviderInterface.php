<?php

namespace Core\Providers;

use DI\ContainerBuilder;

interface AppProviderInterface
{
    public function provide(ContainerBuilder $container, array $definitions);

    public function getTarget(): string;
}
