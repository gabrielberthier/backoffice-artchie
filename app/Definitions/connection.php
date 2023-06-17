<?php

declare(strict_types=1);

use function Core\functions\mode;

return [
    'connection' => static function (): array {
        $exceptionMessage = 'An application mode should be specified at project level .env or _ENV' .
            'variable containing one of the following values: PRODUCTION, TEST or DEV';
        $connectionArray = [];

        if (isset($_ENV['DATABASE_URL'])) {
            $connectionArray['url'] = $_ENV['DATABASE_URL'];
        } else {
            $dbParams = ['DRIVER', 'HOST', 'DBNAME', 'PORT', 'USER', 'PASSWORD', 'CHARSET'];
            foreach ($dbParams as $param) {
                $connectionArray[$param] = $_ENV[$param];
            }
        }

        return match (mode()) {
            'TEST' => [
                'driver' => 'pdo_sqlite',
                'memory' => 'true',
            ],
            'PRODUCTION', 'DEV' => $connectionArray,
            default => throw new Exception($exceptionMessage, 500)
        };
    }
];
