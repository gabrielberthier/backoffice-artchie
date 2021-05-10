<?php

// cli-config.php
declare(strict_types=1);

use Core\Builder\Factories\ContainerFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__.'/configs/bootstrap.php';

$containerFactory = new ContainerFactory();

$container = $containerFactory->get();

$em = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($em);
