<?php

declare(strict_types=1);

namespace Core\Providers\Dependencies;

use Core\Providers\AppProviderInterface;
use DI\ContainerBuilder;

class DependenciesProvider implements AppProviderInterface
{
    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }

    public function getTarget(): string
    {
        return 'dependencies';
    }
}
