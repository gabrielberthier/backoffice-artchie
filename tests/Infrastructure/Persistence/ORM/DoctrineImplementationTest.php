<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Tests\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertDirectoryExists;
use function PHPUnit\Framework\assertIsObject;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertStringNotEqualsFile;
use function PHPUnit\Framework\assertThat;

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
