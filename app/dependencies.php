<?php

declare(strict_types=1);

use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Infrastructure\Cryptography\HashComparer;
use function DI\autowire;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/*
 * Sets infrastructure dependencies
 *
 * @param ContainerBuilder $containerBuilder
 */
return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        ComparerInterface::class => autowire(HashComparer::class),
    ]);
};
