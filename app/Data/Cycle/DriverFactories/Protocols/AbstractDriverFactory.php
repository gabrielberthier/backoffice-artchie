<?php

namespace Core\Data\Cycle\DriverFactories\Protocols;

use Core\Data\Cycle\ConnectionFactories\Input\ConnectionDefinitions;
use Core\Data\Cycle\ConnectionFactories\Protocols\ConnectionConfigInterface;
use Cycle\Database\Config\PDOConnectionConfig;

abstract class AbstractDriverFactory implements DriverFactoryInterface, ConfigurableDriverInterface
{
    public function __construct(
        private ConnectionConfigInterface $connectionConfig,
        protected iterable|string|null $schema = null,
        protected ?string $timezone = null,
        protected ?bool $queryCache = null,
        protected ?bool $readonlySchema = null,
        protected ?bool $readonly = false
    ) {
    }

    public function factory(): DriverFactoryInterface
    {
        return $this;
    }

    public function withSchema(string $schema): static
    {
        $this->schema = $schema;

        return $this;
    }


    public function withTimezone(string $tz): static
    {
        $this->timezone = $tz;

        return $this;
    }

    public function withQueryCache(bool $queryCache): static
    {
        $this->queryCache = $queryCache;

        return $this;
    }

    public function withReadonlySchema(bool $readonlySchema): static
    {
        $this->readonlySchema = $readonlySchema;

        return $this;
    }

    public function readonly(): static
    {
        $this->readonly = true;

        return $this;
    }

    protected function produceConnection(ConnectionDefinitions $connectionDefinitions): PDOConnectionConfig
    {
        if ($connectionDefinitions->url) {
            return $this->connectionConfig->dsnConnection(
                $connectionDefinitions->url,
                $connectionDefinitions->user,
                $connectionDefinitions->password,
                $connectionDefinitions->options
            );
        }

        return $this->connectionConfig->tcpConnection(
            $connectionDefinitions->db,
            $connectionDefinitions->host,
            $connectionDefinitions->port,
            $connectionDefinitions->user,
            $connectionDefinitions->password,
            $connectionDefinitions->options
        );
    }
}
