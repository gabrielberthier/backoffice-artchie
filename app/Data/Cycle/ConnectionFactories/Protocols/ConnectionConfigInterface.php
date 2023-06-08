<?php
namespace Core\Data\Cycle\ConnectionFactories\Protocols;
use Cycle\Database\Config\PDOConnectionConfig;

interface ConnectionConfigInterface
{
    function tcpConnection(
        string $database,
        string $host,
        int|string $port,
        string|null $user,
        string|null $password,
        ?array $options
    ): PDOConnectionConfig;
    function dsnConnection(\Stringable|string $dsn, ?string $user, ?string $password, ?array $options): PDOConnectionConfig;
}