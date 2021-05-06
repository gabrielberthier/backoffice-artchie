<?php

use Doctrine\DBAL\Types\Type;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');

print_r($_ENV);
