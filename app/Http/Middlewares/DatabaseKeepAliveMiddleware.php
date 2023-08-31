<?php

namespace Core\Http\Middlewares;


use Core\Decorators\ReopeningEntityManagerDecorator;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Psr\Log\LoggerInterface;


class DatabaseKeepAliveMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private ReopeningEntityManagerDecorator $reopeningEntityManagerDecorator
    ) {
    }

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $em = $this->reopeningEntityManagerDecorator->open();
        $connection = $em->getConnection();

        if ($connection->isConnected() && !$this->ping($connection)) {
            $this->logger->debug('Doctrine connection was not re-usable, it has been closed');

            $connection->close();
        }

        return $handler->handle($request);
    }

    private function ping(Connection $con): bool
    {
        try {
            $con->executeQuery($con->getDatabasePlatform()->getDummySelectSQL());

            return true;
        } catch (Exception | DBALException) {
            return false;
        }
    }
}