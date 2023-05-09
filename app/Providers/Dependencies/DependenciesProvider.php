<?php

declare(strict_types=1);

namespace Core\Providers\Dependencies;

use Core\Providers\Contract\AbstractProvider;

use DI\ContainerBuilder;

class DependenciesProvider extends AbstractProvider
{
    protected string $target = 'dependencies';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }
}