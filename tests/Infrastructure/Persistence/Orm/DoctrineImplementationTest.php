<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Orm;

use DI\Container;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Tests\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertDirectoryExists;
use function PHPUnit\Framework\assertIsObject;


class DoctrineImplementationTest extends TestCase
{
  public function testSetEnvironmentCorrectly()
  {
    $dir = getcwd();
    assertDirectoryExists($dir . "/src/Domain/Models");
  }

  public function testIfSetupContainerWorks()
  {
    $app = $this->getAppInstance();

    /** @var Container $container */
    $container = $app->getContainer();
    $settings = $container->get("settings");
    $doctrine = $settings["doctrine"];

    assertArrayHasKey("connection", $doctrine);
  }

  public function testIfEntityManagerIsNotNull()
  {
    $app = $this->getAppInstance();

    /** @var Container $container */
    $container = $app->getContainer();
    $em = $container->get(EntityManager::class);

    assertIsObject($em);
  }
}