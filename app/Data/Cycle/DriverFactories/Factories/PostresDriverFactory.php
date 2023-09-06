<?php

namespace Core\Data\Cycle\DriverFactories\Factories;

use Core\Data\Cycle\ConnectionFactories\Input\ConnectionDefinitions;
use Core\Data\Cycle\ConnectionFactories\Connections\PostgresConfig;
use Core\Data\Cycle\DriverFactories\Protocols\AbstractDriverFactory;
use Cycle\Database\Config\DriverConfig;
use Cycle\Database\Config\PostgresDriverConfig;

class PostresDriverFactory extends AbstractDriverFactory
{
    function getDriver(
        ConnectionDefinitions $connectionDefinitions,
        array $options
    ): DriverConfig {
        return new PostgresDriverConfig(
            connection: $this->produceConnection($connectionDefinitions),
            timezone: $this->timezone ?? "UTC",
            schema: $this->schema ?? "public",
            queryCache: $this->queryCache ?? true,
            readonlySchema: $this->readonlySchema ?? false,
            readonly: $this->readonly ?? false,
            options: $options
        );
    }
}
