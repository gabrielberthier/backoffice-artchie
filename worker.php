<?php

declare(strict_types=1);

use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\Http\Factories\RequestFactory;

require __DIR__ . '/configs/bootstrap.php';

$containerFactory = new ContainerFactory();

$containerFactory
    // Set to true in production
    ->enableCompilation(false);

$container = $containerFactory->get();

$appBuilder = new AppBuilderManager($container);

$requestFactory = new RequestFactory();

$request = $requestFactory->createRequest();

$app = $appBuilder->build($request);


/** @var Spiral\RoadRunner\Http\PSR7WorkerInterface $psr7Worker */
$psr7Worker = $app->getContainer()->get(Spiral\RoadRunner\Http\PSR7WorkerInterface::class);


while ($req = $psr7Worker->waitRequest()) {
    try {
        $res = $app->handle($req);
        $psr7Worker->respond($res);
    } catch (Throwable $e) {
        $psr7Worker->getWorker()->error((string) $e);
    }
}