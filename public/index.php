<?php

declare(strict_types=1);

use App\Application\ResponseEmitter\ResponseEmitter;

$app = require __DIR__ . '/../configs/bootstrap.php';

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
