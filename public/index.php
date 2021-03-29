<?php

declare(strict_types=1);


use App\Presentation\ResponseEmitter\ResponseEmitter;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../configs/bootstrap.php';

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
