<?php

// cli-config.php
declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . "/configs/container-setup.php";

/** @var Container $container */
$em = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($em);
