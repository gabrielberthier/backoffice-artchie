<?php

declare(strict_types=1);

use App\Presentation\ResponseEmitter\ResponseEmitter;
use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\HTTP\HTTPRequestFactory;

require __DIR__.'/../configs/bootstrap.php';

$containerFactory = new ContainerFactory();

$containerFactory
// Set to true in production
    ->enableCompilation(false)
// Make use of annotations in classes
    ->withAnnotations()
;

$appBuilder = new AppBuilderManager($containerFactory->get());

$request = (new HTTPRequestFactory())->createRequest();

$app = $appBuilder->build($request);
// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
