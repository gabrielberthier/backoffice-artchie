<?php

declare(strict_types=1);

namespace Core\Providers\Worker;

use Core\Providers\Contract\AbstractProvider;
use DI\ContainerBuilder;

class WorkerProvider extends AbstractProvider
{
    protected string $target = 'worker';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        if (boolval(getenv("RR"))) {
            $container->addDefinitions($definitions);
        }
    }
}