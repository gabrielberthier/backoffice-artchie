<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use Tests\TestCase;


use function PHPUnit\Framework\assertDirectoryExists;
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
}
