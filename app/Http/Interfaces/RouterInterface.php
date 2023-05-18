<?php

namespace Core\Http\Interfaces;

use Slim\Interfaces\RouteCollectorProxyInterface;

interface RouterInterface
{
    public function run(): void;
    public function collect(RouteCollectorProxyInterface $routeCollector): void;
}