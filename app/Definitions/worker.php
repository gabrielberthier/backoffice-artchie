<?php

declare(strict_types=1);

use function DI\autowire;

use Spiral\RoadRunner\Http\PSR7WorkerInterface;

$psr17Factory = new Nyholm\Psr7\Factory\Psr17Factory();

return [
    Psr\Http\Message\ResponseFactoryInterface::class => $psr17Factory,
    Psr\Http\Message\ServerRequestFactoryInterface::class => $psr17Factory,
    Psr\Http\Message\StreamFactoryInterface::class => $psr17Factory,
    Psr\Http\Message\UploadedFileFactoryInterface::class => $psr17Factory,
    Spiral\RoadRunner\WorkerInterface::class => fn() => Spiral\RoadRunner\Worker::create(),
    PSR7WorkerInterface::class => autowire(Spiral\RoadRunner\Http\PSR7Worker::class),
];