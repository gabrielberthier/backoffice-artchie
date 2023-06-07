<?php

namespace Core\Http\Interfaces;

use Psr\Http\Server\MiddlewareInterface;

interface MIddlewareIncluderInterface
{
    function add(\Closure|MiddlewareInterface|string $middleware): void;
}