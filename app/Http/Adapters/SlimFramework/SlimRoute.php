<?php
namespace Core\Http\Adapters\SlimFramework;

use Core\Http\Interfaces\RouteInterface;

class SlimRoute implements RouteInterface
{
    public function __construct(private \Slim\Interfaces\RouteInterface $route)
    {

    }
    function add($middleware): RouteInterface
    {
        $this->route->add($middleware);

        return $this;
    }
    function addMiddleware(\Psr\Http\Server\MiddlewareInterface $middleware): RouteInterface
    {
        $this->route->addMiddleware($middleware);

        return $this;
    }
}