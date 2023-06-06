<?php

namespace Core\Http\Interfaces;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareIncluder
{
    function add(\Closure|MiddlewareInterface|string $middleware): void;
}