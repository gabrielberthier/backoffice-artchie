<?php

namespace Core\Http\Adapters\SlimFramework;

use Core\Http\Interfaces\MIddlewareIncluderInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;

class SlimMiddlewareIncluder implements MIddlewareIncluderInterface
{
    public function __construct(private App $app)
    {

    }
    public function add(\Closure|MiddlewareInterface|string $middleware): void
    {
        $this->app->add($middleware);
    }
}