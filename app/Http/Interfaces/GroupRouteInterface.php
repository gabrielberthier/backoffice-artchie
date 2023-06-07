<?php

namespace Core\Http\Interfaces;

use Psr\Http\Server\MiddlewareInterface;

interface GroupRouteInterface
{
    /**
     * Add middleware to the route group
     *
     * @param MiddlewareInterface|string|callable $middleware
     */
    public function add($middleware): self;

    /**
     * Add middleware to the route group
     */
    public function addMiddleware(MiddlewareInterface $middleware): self;

    /**
     * Get the RouteGroup's pattern
     */
    public function getPattern(): string;
}