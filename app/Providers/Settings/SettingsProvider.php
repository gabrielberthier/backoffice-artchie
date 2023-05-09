<?php

namespace Core\Providers\Settings;

use Core\Providers\Contract\AbstractProvider;
use DI\ContainerBuilder;

class SettingsProvider extends AbstractProvider
{
    protected string $target = 'settings';

    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }
}