<?php

namespace Core\Data\Cycle\Facade;

use Core\Data\Cycle\ConnectionFactories\Input\ConnectionDefinitions;
use Core\Data\Cycle\DriverFactories\Factories\MySqlDriverFactory;
use Core\Data\Cycle\DriverFactories\Factories\PostresDriverFactory;
use Core\Data\Cycle\DriverFactories\Protocols\AbstractDriverFactory;
use Core\Data\Cycle\DriverFactories\Protocols\ConfigurableDriverInterface;
use Cycle\Database\Config\DriverConfig;
use Core\Data\Cycle\ConnectionFactories\Connections\MySqlConfig;
use Core\Data\Cycle\ConnectionFactories\Connections\PostgresConfig;

class ConnectorFacade
{
    private AbstractDriverFactory $driverFactory;

    public function __construct(private array $connection)
    {
        /** @var string */
        $driver =
            $connection["DRIVER"] ??
            explode("://", $connection["url"])[0];

        $this->driverFactory = match ($driver) {
            "postgres",
            "postgresql",
            "pg",
            "pdo_pgsql",
            "pgsql"
            => new PostresDriverFactory(new PostgresConfig()),

            "mysql",
            "pdo_mysql",
            "mysqli"
            => new MySqlDriverFactory(new MySqlConfig()),

            default => throw new \Exception("Driver selection is not correct"),
        };
    }
    public function produceDriverConnection(
        array $driverOptions = [],
        array $connectionOptions = []
    ): DriverConfig {
        return $this->driverFactory->getDriver(
            $this->createInput(
                $this->connection,
                $connectionOptions
            ),
            $driverOptions
        );
    }

    public function configureFactory(): ConfigurableDriverInterface
    {
        return $this->driverFactory;
    }

    private function createInput(
        array $connection,
        array $options
    ): ConnectionDefinitions {
        $db = $connection["DBNAME"] ?? null;
        $port = $connection["PORT"] ?? null;
        $user = $connection["USER"] ?? null;
        $password = $connection["PASSWORD"] ?? null;
        $host = $connection["HOST"] ?? null;
        $url = $connection["url"] ?? null;

        return new ConnectionDefinitions(
            db: $db,
            port: $port,
            user: $user,
            password: $password,
            host: $host,
            url: $url,
            options: $options
        );
    }
}
