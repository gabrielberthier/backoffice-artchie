<?php

namespace Core\Http\Middlewares;


use Core\Decorators\ReopeningEntityManagerDecorator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DatabaseKeepAliveMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ReopeningEntityManagerDecorator $reopeningEntityManagerDecorator,
        private ContainerInterface $containerInterface
    ) {
    }

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->reopeningEntityManagerDecorator->open();

        try {
            return $handler->handle($request);
        } finally {
            $this->reopeningEntityManagerDecorator->getConnection()->close();
            $this->reopeningEntityManagerDecorator->clear();
        }
    }
}