<?php

$mode = $_ENV['MODE'] ?? '';

$scanDatabaseValues = function () use ($mode) {
    $databaseSettings = [
        'driver',
        'host',
        'port',
        'dbname',
        'user',
        'password',
        'charset', ];

    $connectionArray = [];
    $prefix = '';
    if ('DEV' === $mode) {
        $prefix = 'dev';
    }

    foreach ($databaseSettings as $value) {
        $connectionArray[$value] = $_ENV[$prefix.$value];
    }

    return $connectionArray;
};

$connection = match ($mode) {
    'TEST' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'port' => '3306',
        'dbname' => 'backofficeapi',
        'user' => 'root',
        'password' => 'mithrandir99',
        'charset' => 'utf8mb4',
    ],
  'PRODUCTION', 'DEV' => $scanDatabaseValues(),
  default => throw new Exception(
      'An application mode should be specified at project level'
      .'.env or _ENV variable and should contain one of the '
      .'following values: PRODUCTION, TEST or DEV',
      500
  )
};

return $connection;
