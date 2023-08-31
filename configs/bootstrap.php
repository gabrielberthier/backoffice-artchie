<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Error reporting for production
error_reporting(0);
ini_set('display_errors', '0');

// Timezone
date_default_timezone_set('America/Fortaleza');

$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $dotenv = new Dotenv();
    $dotenv->load($envPath);
}