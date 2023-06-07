<?php
namespace Core\Http\Adapters\XFramework;

use Core\Http\Interfaces\RouteCollectorInterface;



class RouteCollector implements RouteCollectorInterface
{
    /**
     * @phpstan-var RouteMap
     *
     * @var array<string, array<string, callable|string>>
     */
    private array $routes = [];

    /**
     * @phpstan-var GroupMap
     *
     * @var array<string, RouteCollector>
     */
    private array $groups = [];

    public function __construct(private readonly App $app, private readonly string $basePath)
    {
    }

    /**
     * @param callable(RouteCollector): void $callback
     */
    public function addGroup(string $path, callable $callback): void
    {
        $path = $this->normalisePath($path);
        $next = new self($this->app, $path);
        $this->groups[$path] = $next;

        $callback($next);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function get(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['GET'] = $callback;

        $this->app->get($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function post(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['POST'] = $callback;

        $this->app->post($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function put(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['PUT'] = $callback;

        $this->app->put($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function patch(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['PATCH'] = $callback;

        $this->app->patch($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function delete(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['DELETE'] = $callback;

        $this->app->delete($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function options(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['OPTIONS'] = $callback;

        $this->app->options($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function head(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }

        $this->routes[$path]['HEAD'] = $callback;

        $this->app->head($path, $callback);
    }

    /**
     * @phpstan-param RouteHandler $callback
     */
    public function any(string $path, callable |string $callback): void
    {
        $path = $this->normalisePath($path);

        $this->get($path, $callback);
        $this->post($path, $callback);
        $this->put($path, $callback);
        $this->patch($path, $callback);
        $this->delete($path, $callback);
        $this->options($path, $callback);
        $this->head($path, $callback);
    }

    /**
     * @phpstan-return RouteMap
     *
     * @return array<string, array<HttpVerb, RouteHandler>>
     */
    public function getRoutes(): array
    {
        $routes = array_merge([], $this->routes);
        $groups = array_merge([], $this->groups);

        while (count($groups) > 0) {
            $group = array_shift($groups);
            $routes = array_merge($routes, $group->getRoutes());
            $groups = array_merge($groups, $group->getGroups());
        }

        return array_merge($this->routes, $routes);
    }

    /**
     * @phpstan-return GroupMap
     *
     * @return array<string, RouteCollector>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    private function normalisePath(string $path): string
    {
        if (str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }

        return $this->basePath . $path;
    }
}