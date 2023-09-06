<?php
namespace Core\Data\BehaviourComponents;

use Doctrine\ORM\Tools\SchemaTool;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Cycle\ORM;

class DatabaseCreator
{
    public static function create(ContainerInterface $containerInterface): void
    {
        if (boolval(getenv("RR"))) {
            DatabaseCreator::createCycleDatabase($containerInterface);
        } else {
            DatabaseCreator::createDoctrineDatabase($containerInterface);
        }
    }

    public static function createDoctrineDatabase(ContainerInterface $containerInterface): void
    {
        $entityManager = $containerInterface->get(EntityManager::class);
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadatas);
    }

    public static function createCycleDatabase(ContainerInterface $containerInterface): void
    {
        /** @var \Cycle\ORM\ORM */
        $orm = $containerInterface->get(ORM\ORM::class);

        $orm->prepareServices();
    }
}