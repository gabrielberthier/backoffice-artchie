<?php

namespace Core;

class ResourceLoader
{
    private static string $defaultPath = __DIR__.'/Definitions/';

    public static function getResource(string $file): array
    {
        $resource = require self::$defaultPath.$file.'.php';

        return $resource;
    }
}
