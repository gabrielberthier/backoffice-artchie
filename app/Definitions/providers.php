<?php

use Core\Providers\Connection\ConnectionProvider;
use Core\Providers\Database\DatabaseProvider;
use Core\Providers\Dependencies\DependenciesProvider;
use Core\Providers\Repository\RepositoriesProvider;
use Core\Providers\Services\ServicesProvider;
use Core\Providers\Worker\WorkerProvider;
use Core\Providers\Settings\SettingsProvider;


/**
 * @var \Core\Providers\AppProviderInterface[]
 */
return [
    ConnectionProvider::class,
    DatabaseProvider::class,
    DependenciesProvider::class,
    RepositoriesProvider::class,
    ServicesProvider::class,
    SettingsProvider::class,
    WorkerProvider::class,
];