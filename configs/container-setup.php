<?php

use DI\ContainerBuilder;

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
  $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Enable ORM
$ormMaker = require __DIR__ . "/../app/database-setup.php";
$ormMaker($containerBuilder);

// Build PHP-DI Container instance
$containerBuilder->useAnnotations(true);
$container = $containerBuilder->build();

return $container;
