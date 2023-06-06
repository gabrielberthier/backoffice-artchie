<?php

declare(strict_types=1);


use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Core\Data\Doctrine\EntityManagerBuilder;

return [
    EntityManager::class => static function (ContainerInterface $container): EntityManager {
        $settings = $container->get('settings');
        $doctrine = $settings['doctrine'];

        return EntityManagerBuilder::produce($doctrine);
    },
];