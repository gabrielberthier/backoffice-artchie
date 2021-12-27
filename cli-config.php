<?php

// cli-config.php
declare(strict_types=1);

use Core\Builder\Factories\ContainerFactory;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;

require __DIR__.'/configs/bootstrap.php';

$containerFactory = new ContainerFactory();

$container = $containerFactory->get();

$config = new PhpFile('migrations.php');

$em = $container->get(EntityManager::class);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($em));
