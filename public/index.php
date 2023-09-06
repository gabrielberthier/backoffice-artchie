<?php

declare(strict_types=1);

use App\Presentation\ResponseEmitter\ResponseEmitter;
use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\Http\Factories\RequestFactory;
use function Core\functions\isProd;

require __DIR__ . '/../configs/bootstrap.php';

$containerFactory = new ContainerFactory();

$containerFactory
    // Set to true in production
    ->enableCompilation(isProd())
;

$appBuilder = new AppBuilderManager($containerFactory->get());
$requestFactory = new RequestFactory();
$request = $requestFactory->createRequest();

$app = $appBuilder->build($request);
// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);