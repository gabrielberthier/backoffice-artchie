<?php

namespace Core\Http\Interfaces;


interface RouterInterface
{
    public function run(): void;
    public function collect(RouteCollectorInterface $routeCollector): void;
}