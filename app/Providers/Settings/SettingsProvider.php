<?php

namespace Core\Providers\Settings;

use Core\Providers\AppProviderInterface;
use DI\ContainerBuilder;

class SettingsProvider implements AppProviderInterface
{
    public function provide(ContainerBuilder $container, array $definitions)
    {
        $container->addDefinitions($definitions);
    }

    public function getTarget(): string
    {
        return 'settings';
    }
}
