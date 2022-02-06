<?php

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

return [
    EntityManager::class => function (ContainerInterface $container): EntityManager {
        $settings = $container->get('settings');
        $doctrine = $settings['doctrine'];

        $config = Setup::createAnnotationMetadataConfiguration(
            $doctrine['metadata_dirs'],
            $doctrine['dev_mode'],
            useSimpleAnnotationReader: false
        );


        // $config->setMetadataCacheImpl(
        //     new FilesystemCache(
        //         $doctrine['cache_dir']
        //     )
        // );

        if (!Type::hasType('uuid')) {
            Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }

        $entityManager = EntityManager::create(
            $doctrine['connection'],
            $config
        );

        if (!Type::hasType('uuid_binary')) {
            Type::addType('uuid_binary', 'Ramsey\Uuid\Doctrine\UuidBinaryType');
            $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('uuid_binary', 'binary');
        }

        return $entityManager;
    },
];
