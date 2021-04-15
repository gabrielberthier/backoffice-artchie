<?php

declare(strict_types=1);

namespace Core\Providers\Database;

use Core\Providers\AppProviderInterface;
use DI\ContainerBuilder;

class DatabaseProvider implements AppProviderInterface
{
    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }

    public function getTarget(): string
    {
        return 'database';
    }
}
