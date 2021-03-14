<?php


declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
  $containerBuilder->addDefinitions([
    EntityManager::class => function (ContainerInterface $container): EntityManager {
      $config = Setup::createAnnotationMetadataConfiguration(
        $container->get('settings')['doctrine']['metadata_dirs'],
        $container->get('settings')['doctrine']['dev_mode']
      );

      $config->setMetadataDriverImpl(
        new AnnotationDriver(
          new AnnotationReader,
          $container->get('settings')['doctrine']['metadata_dirs']
        )
      );

      $config->setMetadataCacheImpl(
        new FilesystemCache(
          $container->get('settings')['doctrine']['cache_dir']
        )
      );

      return EntityManager::create(
        $container->get('settings')['doctrine']['connection'],
        $config
      );
    },
  ]);
};

return $container;
