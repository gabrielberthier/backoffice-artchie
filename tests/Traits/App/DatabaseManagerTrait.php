<?php

namespace Tests\Traits\App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Container\ContainerInterface;

trait DatabaseManagerTrait
{
    final public function createDatabase()
    {
        /** @var ContainerInterface */
        $container = $this->getContainer();
        /** @var EntityManager */
        $entityManager = $container->get(EntityManager::class);
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadatas);
    }

    final public function truncateDatabase()
    {
        /** @var EntityManager */
        $entityManager = $this->getContainer();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
    }
}
