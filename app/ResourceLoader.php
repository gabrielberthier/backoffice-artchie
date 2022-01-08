<?php

namespace Core;

class ResourceLoader
{
    private static string $defaultPath = __DIR__.'/Definitions/';

    /**
     * Retrieves array values defined in a file at Definitions folder.
     */
    public static function getResource(string $file): array
    {
        $resource = require self::$defaultPath.$file.'.php';

        return $resource;
    }
}
