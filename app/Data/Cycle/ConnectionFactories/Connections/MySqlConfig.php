<?php
namespace Core\Data\Cycle\ConnectionFactories\Connections;

use Core\Data\Cycle\ConnectionFactories\Protocols\ConnectionConfigInterface;
use Cycle\Database\Config;
use Cycle\Database\Config\MySQL\ConnectionConfig;

class MySqlConfig implements ConnectionConfigInterface
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
        $charset = $options['charset'] ?? 'utf8mb4';

        return new Config\MySQL\TcpConnectionConfig(
            $database,
            $host ?? "localhost",
            $port ?? 5432,
            $charset,
            $user,
            $password,
            $options
        );
    }
    public function dsnConnection(\Stringable|string $dsn, ?string $user, ?string $password, ?array $options): ConnectionConfig
    {
        $options ??= [];

        return new Config\MySQL\DsnConnectionConfig(
            $dsn,
            $user,
            $password,
            $options
        );
    }
}