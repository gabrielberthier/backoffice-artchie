<?php

declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\Traits\App\AppTestTrait;
use Tests\Traits\App\DoublesTrait;
use Tests\Traits\App\ErrorHandlerTrait;
use Tests\Traits\App\InstanceManagerTrait;
use Tests\Traits\App\RequestManagerTrait;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends PHPUnit_TestCase
{
    use AppTestTrait;
    use DoublesTrait;
    use ErrorHandlerTrait;
    use InstanceManagerTrait;
    use RequestManagerTrait;

    public static function createDatabase()
    {
        /** @var \Psr\Container\ContainerInterface */
        $container = self::$container;
        /** @var EntityManager */
        $entityManager = $container->get(EntityManager::class);
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadatas);
    }

    final public static function truncateDatabase()
    {
        /** @var \Psr\Container\ContainerInterface */
        $container = self::$container;
        /** @var EntityManager */
        $entityManager = $container->get(EntityManager::class);
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
    }
}