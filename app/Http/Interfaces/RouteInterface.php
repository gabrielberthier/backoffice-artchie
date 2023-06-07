<?php
namespace Core\Http\Interfaces;

use Psr\Http\Server\MiddlewareInterface;

interface RouteInterface
{
    /**
     * @param MiddlewareInterface|string|callable $middleware
     */
    public function add($middleware): self;

    public function addMiddleware(MiddlewareInterface $middleware): self;
}