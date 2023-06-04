<?php

declare(strict_types=1);

namespace Core\Providers\Services;

use Core\Providers\Contract\AbstractProvider;
use function DI\autowire;
use DI\ContainerBuilder;

class WorkerProvider extends AbstractProvider
{
    protected string $target = 'worker';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }
}