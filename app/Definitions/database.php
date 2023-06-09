<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Core\Data\Doctrine\EntityManagerBuilder;

use Cycle\Database\Config;
use Cycle\Database\DatabaseManager;
use Cycle\Database\Config\DatabaseConfig;
use Core\Data\Cycle\Facade\ConnectorFacade;

return [
    EntityManagerInterface::class => static fn (
        ContainerInterface $container
    ) => EntityManagerBuilder::produce(
        $container->get("settings")["doctrine"]
    ),

    DatabaseManager::class => static function (
        ContainerInterface $container
    ): DatabaseManager {
        $connectorFacade = new ConnectorFacade(
            connection: $container->get("connection")
        );

        // Configure connector as you wish
        $connectorFacade
            ->configureFactory()
            ->withQueryCache(true)
            ->withSchema("public");

        return new DatabaseManager(
            new DatabaseConfig([
                "default" => "default",
                "databases" => [
                    "default" => ["connection" => "production"],
                ],
                "connections" => [
                    "sqlite" => new Config\SQLiteDriverConfig(
                        connection: new Config\SQLite\MemoryConnectionConfig(),
                        queryCache: true
                    ),
                    "production" => $connectorFacade->produceDriverConnection(),
                ],
            ])
        );
    },
];
