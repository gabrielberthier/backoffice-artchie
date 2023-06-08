<?php
namespace Core\Data\Cycle\ConnectionFactories\Connections;

use Core\Data\Cycle\ConnectionFactories\Protocols\ConnectionConfigInterface;
use Cycle\Database\Config;
use Cycle\Database\Config\Postgres\ConnectionConfig;

class PostgresConfig implements ConnectionConfigInterface
{
    public function tcpConnection(
        string $database,
        string $host,
        int|string $port,
        string|null $user,
        string|null $password,
        ?array $options
    ): ConnectionConfig {
        $options ??= [];

        return new Config\Postgres\TcpConnectionConfig(
            $database,
            $host ?? "localhost",
            $port ?? 5432,
            $user,
            $password,
            $options
        );
    }
    public function dsnConnection(\Stringable|string $dsn, ?string $user, ?string $password, ?array $options): ConnectionConfig
    {
        $options ??= [];

        return new Config\Postgres\DsnConnectionConfig(
            $dsn,
            $user,
            $password,
            $options
        );
    }
}