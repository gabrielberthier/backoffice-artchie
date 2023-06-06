<?php

namespace Core\Builder;

use Core\Http\Interfaces\MiddlewareIncluder;
use Core\ResourceLoader;
use Middlewares\TrailingSlash;


class MiddlewareCollector
{
    public static function collect(MiddlewareIncluder $root)
    {
        $root->add(new TrailingSlash());

        // Apply middlewares
        $definitions = ResourceLoader::getResource('middlewares');
        foreach ($definitions as $middlewareClass) {
            $root->add($middlewareClass);
        }
    }
}