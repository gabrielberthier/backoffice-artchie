<?php

declare(strict_types=1);

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Middleware\ErrorMiddleware;

return [
  'settings' => [
    'displayErrorDetails' => true, // Should be set to false in production
    'logger' => [
      'name' => 'slim-app',
      'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
      'level' => Logger::DEBUG,
    ],
    'db' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'username' => 'root',
      'database' => 'test',
      'password' => '',
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
      'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
      ],
    ]
  ],
  // ErrorMiddleware::class => function (ContainerInterface $container) {
  //   $app = $container->get(App::class);
  //   $settings = $container->get('settings')['error'];

  //   return new ErrorMiddleware(
  //     $app->getCallableResolver(),
  //     $app->getResponseFactory(),
  //     (bool)$settings['display_error_details'],
  //     (bool)$settings['log_errors'],
  //     (bool)$settings['log_error_details']
  //   );
  // },
];
