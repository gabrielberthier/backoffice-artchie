<?php

declare(strict_types=1);

namespace Core\Providers\Services;

use Core\Providers\Contract\AbstractProvider;
use function DI\autowire;
use DI\ContainerBuilder;

class ServicesProvider extends AbstractProvider
{
    protected string $target = 'services';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        $serviceMapper = [];
        foreach ($definitions as $key => $value) {
            $serviceMapper[$key] = autowire($value);
        }
        // Here we map our UserRepository interface to its in memory implementation
        $container->addDefinitions($serviceMapper);
    }
}