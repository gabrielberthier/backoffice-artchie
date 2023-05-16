<?php

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return [
    EntityManager::class => static function (ContainerInterface $container): EntityManager {
        $settings = $container->get('settings');
        $doctrine = $settings['doctrine'];

        $cache = $doctrine['dev_mode'] ?
            DoctrineProvider::wrap(new ArrayAdapter()) :
            DoctrineProvider::wrap(new FilesystemAdapter(directory: $doctrine['cache_dir']));

        $config = Setup::createAttributeMetadataConfiguration(
            $doctrine['metadata_dirs'],
            $doctrine['dev_mode'],
            null,
            $cache
        );

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
