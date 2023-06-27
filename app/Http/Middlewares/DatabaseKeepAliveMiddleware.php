<?php

namespace Core\Http\Middlewares;


use Core\Decorators\ReopeningEntityManagerDecorator;
use DI\Container;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DatabaseKeepAliveMiddleware implements MiddlewareInterface
{
    private ReopeningEntityManagerDecorator $reopeningEntityManagerDecorator;

    public function __construct(
        private Container $container
    ) {
        $this->reopeningEntityManagerDecorator = new ReopeningEntityManagerDecorator($container);
        $container->set(EntityManagerInterface::class, $this->reopeningEntityManagerDecorator->open());
    }

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        echo "cALLED";
        $em = $this->reopeningEntityManagerDecorator->open();
        $this->container->set(EntityManagerInterface::class, $em);

        try {
            return $handler->handle($request);
        } finally {
            $this->reopeningEntityManagerDecorator->getConnection()->close();
            $this->reopeningEntityManagerDecorator->clear();
        }
    }
}