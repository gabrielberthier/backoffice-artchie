<?php

declare(strict_types=1);

namespace Core\Providers\Services;

use Core\Providers\AppProviderInterface;
use function DI\autowire;
use DI\ContainerBuilder;

class ServicesProvider implements AppProviderInterface
{
    public function provide(ContainerBuilder $container, array $definitions)
    {
        $serviceMapper = [];
        foreach ($definitions as $key => $value) {
            $serviceMapper[$key] = autowire($value);
        }
        // Here we map our UserRepository interface to its in memory implementation
        $container->addDefinitions($serviceMapper);
    }

    public function getTarget(): string
    {
        return 'services';
    }
}
