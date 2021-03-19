<?php

declare(strict_types=1);

namespace App\Data\UseCases\Authentication\Errors;

use Exception;

class IncorrectPasswordException extends Exception
{
    public $message = "The passwords don't match";
}
