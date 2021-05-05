<?php

namespace App\Infrastructure\Cryptography\Exceptions;

use Exception;

final class AppHasNoDefinedSecrets extends Exception
{
    public $message;

    public function __construct($field)
    {
        $this->message = "App has no defined secret for {$field}";
    }
}
