<?php

namespace Core\Providers\Connection;

use Core\Providers\Contract\AbstractProvider;
use DI\ContainerBuilder;

class ConnectionProvider extends AbstractProvider
{
    protected string $target = 'connection';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }
}