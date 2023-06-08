<?php

use Cycle\Database\Config;

/** @var array */
$connectionArray = require __DIR__.'/connection.php';

return [
    'sqlite_memory' => new Config\SQLiteDriverConfig(
        connection: new Config\SQLite\MemoryConnectionConfig(),
        queryCache: true,
    ),

];