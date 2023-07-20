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
use Exception;

class ConnectorFacade
{
    private ?AbstractDriverFactory $driverFactory = null;
    private string $driver;

    private ConnectionDefinitions $connectionDefinitions;

    public function __construct(private array $connection, array $connectionOptions = [])
    {
        /** @var string */
        $this->driver = $this->prepareDriverSelection($connection);
        $this->connectionDefinitions = $this->createInput($connection, $connectionOptions);
    }

    public function produceDriverConnection(
        array $driverOptions = [],
    ): DriverConfig {
        return $this
            ->getFactory()
            ->getDriver(
                $this->connectionDefinitions,
                $driverOptions
            );
    }

    public function configureFactory(): ConfigurableDriverInterface
    {
        return $this->getFactory();
    }

    private function getFactory(): AbstractDriverFactory
    {
        if ($this->driverFactory === null) {
            $this->startFactory();
        }
        return $this->driverFactory;
    }

    private function prepareDriverSelection(array $connection): string
    {
        return $connection["DRIVER"] ?? explode("://", $connection["url"])[0];
    }

    private function startFactory()
    {
        $this->driverFactory = match ($this->driver) {
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

            default => throw new Exception("Driver selection is not correct"),
        };
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