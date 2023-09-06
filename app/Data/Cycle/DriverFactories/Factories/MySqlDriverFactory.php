<?php

namespace Core\Data\Cycle\DriverFactories\Factories;


use Core\Data\Cycle\ConnectionFactories\Input\ConnectionDefinitions;
use Cycle\Database\Config\MySQLDriverConfig;
use Core\Data\Cycle\DriverFactories\Protocols\AbstractDriverFactory;
use Cycle\Database\Config\DriverConfig;


class MySqlDriverFactory extends AbstractDriverFactory
{
    function getDriver(
        ConnectionDefinitions $connectionDefinitions,
        array $options
    ): DriverConfig {
        return new MySQLDriverConfig(
            connection: $this->produceConnection($connectionDefinitions),
            timezone: $this->timezone ?? "UTC",
            queryCache: $this->queryCache ?? true,
            readonlySchema: $this->readonlySchema ?? false,
            readonly: $this->readonly ?? false,
            options: $options
        );
    }
}
