<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
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
            $doctrine['dev_mode']
        );

        $config->setMetadataDriverImpl(
            new AnnotationDriver(
                new AnnotationReader(),
                $doctrine['metadata_dirs']
            )
        );

        $config->setMetadataCacheImpl(
            new FilesystemCache(
                $doctrine['cache_dir']
            )
        );

        return EntityManager::create(
            $doctrine['connection'],
            $config
        );
    },
];
