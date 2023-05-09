<?php

declare(strict_types=1);

namespace Core\Providers\Database;

use Core\Providers\Contract\AbstractProvider;
use DI\ContainerBuilder;

class DatabaseProvider extends AbstractProvider
{
    protected string $target = 'database';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }
}