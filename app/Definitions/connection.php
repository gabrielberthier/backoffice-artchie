<?php

$mode = $_ENV['MODE'] ?? '';

$scanDatabaseValues = function (): array {
    if (isset($_ENV['DATABASE_URL'])) {
        return ['url' => $_ENV['DATABASE_URL']];
    }

    $databaseSettings = [
        'DRIVER',
        'HOST',
        'DBNAME',
        'PORT',
        'USER',
        'PASSWORD',
        'CHARSET',
    ];

    $connectionArray = [];

    foreach ($databaseSettings as $value) {
        $connectionArray[$value] = $_ENV[$value];
    }

    return $connectionArray;
};

$connection = match ($mode) {
    'TEST' => [
        'driver' => 'pdo_sqlite',
        'memory' => 'true',
    ],
    'PRODUCTION', 'DEV' => $scanDatabaseValues(),
    default => throw new Exception(
        'An application mode should be specified at project level'
            . ' .env or _ENV variable and should contain one of the '
            . 'following values: PRODUCTION, TEST or DEV',
        500
    )
};

return $connection;
