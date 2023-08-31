<?php

namespace Core\Data\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;

class EntityManagerBuilder
{
    public static function produce(array $doctrine): EntityManagerInterface
    {
        $devMode = $doctrine['dev_mode'];

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $doctrine['metadata_dirs'],
            $devMode
        );

        if (!Type::hasType('uuid')) {
            Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }

        $connection = DriverManager::getConnection(
            $doctrine['connection'],
            $config
        );

        $entityManager = new EntityManager($connection, $config);

        if (!Type::hasType('uuid_binary')) {
            Type::addType('uuid_binary', 'Ramsey\Uuid\Doctrine\UuidBinaryType');
            $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('uuid_binary', 'binary');
        }

        return $entityManager;
    }
}