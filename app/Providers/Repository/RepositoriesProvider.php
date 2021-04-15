<?php

declare(strict_types=1);

namespace Core\Providers\Repository;

use Core\Providers\AppProviderInterface;
use function DI\autowire;
use DI\ContainerBuilder;

class RepositoriesProvider implements AppProviderInterface
{
    public function provide(ContainerBuilder $container, array $definitions)
    {
        $repositories = [];
        foreach ($definitions as $key => $value) {
            $repositories[$key] = autowire($value);
        }
        // Here we map our UserRepository interface to its in memory implementation
        $container->addDefinitions($repositories);
    }

    public function getTarget(): string
    {
        return 'repositories';
    }
}
