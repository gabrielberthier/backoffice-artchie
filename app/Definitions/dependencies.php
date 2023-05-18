<?php

declare(strict_types=1);

use App\Data\Protocols\AsymCrypto\AsymmetricVerifier;
use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\Protocols\Cryptography\DataDecrypter;
use App\Data\Protocols\Cryptography\DataEncrypter;
use App\Data\Protocols\Cryptography\HasherInterface;
use App\Infrastructure\Cryptography\AsymmetricKeyGeneration\AsymmetricOpenSSLVerifier;
use App\Infrastructure\Cryptography\AsymmetricKeyGeneration\OpenSSLAsymmetricEncrypter;
use App\Infrastructure\Cryptography\DataEncryption\Encrypter;
use App\Infrastructure\Cryptography\HashComparer;
use App\Infrastructure\Cryptography\HashCreator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use S3DataTransfer\Interfaces\Download\StreamCollectorInterface;
use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use S3DataTransfer\S3\Factories\S3AsyncDownloaderFactory;
use S3DataTransfer\S3\Factories\S3AsyncUploadingFactory;
use S3DataTransfer\S3\Zip\S3StreamObjectsZipDownloader;
use League\OAuth2\Client\Provider\Google;

/*
 * Sets infrastructure dependencies
 *
 * @param ContainerBuilder $containerBuilder
 */

$encrypter = new Encrypter($_SERVER['ENCRYPTION_KEY'] ?? '');

return [
    LoggerInterface::class => static function (ContainerInterface $c) {
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
    AsymmetricEncrypter::class => new OpenSSLAsymmetricEncrypter(),
    AsymmetricVerifier::class => new AsymmetricOpenSSLVerifier(),
    StreamCollectorInterface::class => static function (ContainerInterface $c): StreamCollectorInterface {
        $factory = new S3AsyncDownloaderFactory();
        $key = $_ENV['S3KEY'];
        $secret = $_ENV['S3SECRET'];
        $region = $_ENV['S3REGION'];
        $version = $_ENV['S3VERSION'];

        return $factory->create($key, $secret, $region, $version);
    },
    S3StreamObjectsZipDownloader::class => static function (ContainerInterface $container): S3StreamObjectsZipDownloader {
        /**
         * @var StreamCollectorInterface
         */
        $streamCollector = $container->get(StreamCollectorInterface::class);

        return new S3StreamObjectsZipDownloader($streamCollector);
    },
    UploadCollectorInterface::class => static function (ContainerInterface $c): UploadCollectorInterface {
        $factory = new S3AsyncUploadingFactory();
        $key = $_ENV['S3KEY'];
        $secret = $_ENV['S3SECRET'];
        $region = $_ENV['S3REGION'];
        $version = $_ENV['S3VERSION'];

        return $factory->create($key, $secret, $region, $version);
    },
    Google::class => static function (ContainerInterface $c): Google {
        $clientId = $_ENV['GOOGLE_CLIENT_ID'];
        $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
        $redirectUri = $_ENV['GOOGLE_REDIRECT_URI'];
        
        return new Google(compact('clientId', 'clientSecret', 'redirectUri'));
    }
];