<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    'environment' => $_ENV['MODE'] ?? '',
    'connection' => static function (ContainerInterface $c): array {
        $scanDatabase = function (): array {
            $connectionArray = [];

            if (isset($_ENV['DATABASE_URL'])) {
                $connectionArray['url'] = $_ENV['DATABASE_URL'];
            } else {
                $databaseSettings = ['DRIVER', 'HOST', 'DBNAME', 'PORT', 'USER', 'PASSWORD', 'CHARSET'];

                foreach ($databaseSettings as $value) {
                    $connectionArray[$value] = $_ENV[$value];
                }
            }

            return $connectionArray;
        };

        return match ($c->get('environment')) {
            'TEST' => [
                'driver' => 'pdo_sqlite',
                'memory' => 'true',
            ],
            'PRODUCTION', 'DEV' => $scanDatabase(),
            default => throw new Exception(
                'An application mode should be specified at project level'
                . ' .env or _ENV variable and should contain one of the '
                . 'following values: PRODUCTION, TEST or DEV',
                500
            )
        };
    }
];