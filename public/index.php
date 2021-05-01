<?php

declare(strict_types=1);

use App\Presentation\ResponseEmitter\ResponseEmitter;
use Core\Builder\AppBuilderManager;
use Core\Builder\Factories\ContainerFactory;
use Core\HTTP\HTTPRequestFactory;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$appBuilder = new AppBuilderManager(new ContainerFactory());

$request = (new HTTPRequestFactory())->createRequest();

return $appBuilder->build($request);
// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
