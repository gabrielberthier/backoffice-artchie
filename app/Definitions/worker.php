<?php

declare(strict_types=1);

use function DI\autowire;

return [
    Spiral\RoadRunner\WorkerInterface::class => Spiral\RoadRunner\Worker::create(),
    Spiral\RoadRunner\Http\PSR7WorkerInterface::class => autowire(Spiral\RoadRunner\Http\PSR7Worker::class),
];