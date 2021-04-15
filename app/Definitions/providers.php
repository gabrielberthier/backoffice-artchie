<?php
/**
 * @var AppProviderInterface[]
 */

use Core\Providers\Database\DatabaseProvider;
use Core\Providers\Dependencies\DependenciesProvider;
use Core\Providers\Repository\RepositoriesProvider;
use Core\Providers\Services\ServicesProvider;
use Core\Providers\Settings\SettingsProvider;

return [
    DatabaseProvider::class,
    DependenciesProvider::class,
    RepositoriesProvider::class,
    ServicesProvider::class,
    SettingsProvider::class,
];
