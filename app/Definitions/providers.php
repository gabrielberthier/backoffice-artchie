<?php
use Core\Providers\Database\DatabaseProvider;
use Core\Providers\Dependencies\DependenciesProvider;
use Core\Providers\Repository\RepositoriesProvider;
use Core\Providers\Services\ServicesProvider;
use Core\Providers\Settings\SettingsProvider;


/**
 * @var \Core\Providers\AppProviderInterface[]
 */
return [
    DatabaseProvider::class,
    DependenciesProvider::class,
    RepositoriesProvider::class,
    ServicesProvider::class,
    SettingsProvider::class,
];