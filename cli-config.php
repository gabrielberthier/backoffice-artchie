<?php

// cli-config.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\App;
use Slim\Container;

/** @var App $container */
$app = require_once __DIR__ . '/configs/bootstrap.php';

/** @var Container $container */
$container = $app->getContainer();
$em = $container->get(EntityManager::class);
return ConsoleRunner::createHelperSet($em);
