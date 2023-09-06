<?php

declare(strict_types=1);

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use function Core\functions\isDev;

$root = dirname(dirname(__DIR__));

return [
    'root' => $root,
    'temp' => $root . '/tmp',
    'public' => $root . '/public',
    'settings' => [
        'displayErrorDetails' => true,
        // Should be set to false in production
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : $root . '/logs/app.log',
            'level' => Logger::DEBUG,
        ],
        'doctrine' => static function (ContainerInterface $c): array {
            return [
                // if true, metadata caching is forcefully disabled
                'dev_mode' => isDev(),

                // path where the compiled metadata info will be cached
                // make sure the path exists and it is writable
                'cache_dir' => getcwd() . '/var/doctrine',

                // you should add any other path containing annotated entity classes
                'metadata_dirs' => [getcwd() . '/src/Data/Entities/Doctrine'],

                'connection' => $c->get('connection'),
            ];
        },
    ],
];
