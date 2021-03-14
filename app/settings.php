<?php

declare(strict_types=1);

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerSettings = require(__DIR__ . "/../configs/container.php");
    $containerBuilder->addDefinitions($containerSettings);
};
