<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

$envPath = __DIR__.'/../.env';
if (file_exists($envPath)) {
    $dotenv = new Dotenv();
    $dotenv->load($envPath);
}
