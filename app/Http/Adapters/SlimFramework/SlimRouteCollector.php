<?php

namespace Core\Http\Adapters\SlimFramework;

use Core\Http\Interfaces\GroupRouteInterface;
use Core\Http\Interfaces\RouteCollectorInterface;
use Core\Http\Interfaces\RouteInterface;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface as SlimRouteCollectorInterface;

class SlimRouteCollector implements RouteCollectorInterface
{

    public function __construct(private readonly SlimRouteCollectorInterface $slimRouteCollectorInterface)
    {

    }

    public function getContainer(): ?ContainerInterface
    {
        return $this->slimRouteCollectorInterface->getContainer();
    }

    /**
     * Get the RouteCollectorProxy's base path
     */
    public function getBasePath(): string
    {
        return $this->slimRouteCollectorInterface->getBasePath();
    }

    /**
     * Set the RouteCollectorProxy's base path
     */
    public function setBasePath(string $basePath): self
    {
        $this->slimRouteCollectorInterface->setBasePath($basePath);

        return $this;
    }

    /**
     * Add GET route
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function get(string $pattern, $callable): RouteInterface
    {
        return $this->map(['GET'], $pattern, $callable);
    }

    /**
     * Add POST route
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function post(string $pattern, $callable): RouteInterface
    {
        return $this->map(['POST'], $pattern, $callable);

    }

    /**
     * Add PUT route
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function put(string $pattern, $callable): RouteInterface
    {
        return $this->map(['PUT'], $pattern, $callable);

    }

    /**
     * Add PATCH route
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function patch(string $pattern, $callable): RouteInterface
    {
        return $this->map(['PATCH'], $pattern, $callable);
    }

    /**
     * Add DELETE route
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function delete(string $pattern, $callable): RouteInterface
    {
        return $this->map(['DELETE'], $pattern, $callable);

    }

    /**
     * Add OPTIONS route
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function options(string $pattern, $callable): RouteInterface
    {
        return $this->map(['OPTIONS'], $pattern, $callable);
    }

    /**
     * Add route for any HTTP method
     *
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function any(string $pattern, $callable): RouteInterface
    {
        return $this->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $pattern, $callable);
    }

    /**
     * Add route with multiple methods
     *
     * @param  string[]        $methods  Numeric array of HTTP method names
     * @param  string          $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     */
    public function map(array $methods, string $pattern, $callable): RouteInterface
    {
        return new SlimRoute($this->slimRouteCollectorInterface->map($methods, $pattern, $callable));
    }

    /**
     * Route Groups
     *
     * This method accepts a route pattern and a callback. All route
     * declarations in the callback will be prepended by the group(s)
     * that it is in.
     * 
     * @param string $pattern
     * @param callable(RouteCollectorInterface): void $callback
     */
    public function group(string $pattern, callable $callable): GroupRouteInterface
    {
        $group = $this->slimRouteCollectorInterface->group($pattern, $callable);

        return new SlimGroupRoute($group);
    }

    /**
     * Add a route that sends an HTTP redirect
     *
     * @param string|\Psr\Http\Message\UriInterface $to
     */
    public function redirect(string $from, $to, int $status = 302): RouteInterface
    {
        return new SlimRoute($this->slimRouteCollectorInterface->redirect($from, $to, $status));
    }
}