<?php

declare(strict_types=1);

use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\Protocols\Cryptography\DataDecrypter;
use App\Data\Protocols\Cryptography\DataEncrypter;
use App\Data\Protocols\Cryptography\HasherInterface;
use App\Infrastructure\Cryptography\DataEncryption\Encrypter;
use App\Infrastructure\Cryptography\HashComparer;
use App\Infrastructure\Cryptography\HashCreator;
use App\Infrastructure\Downloader\S3\S3DownloaderFactory;
use App\Infrastructure\Downloader\S3\StreamResourceCollectorInterface;
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

 $encrypter = new Encrypter($_SERVER['ENCRYPTION_KEY'] ?? '');

return [
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
    ComparerInterface::class => new HashComparer(),
    HasherInterface::class => new HashCreator(),
    DataDecrypter::class => $encrypter,
    DataEncrypter::class => $encrypter,
    StreamResourceCollectorInterface::class => S3DownloaderFactory::create(),
];
