<?php

namespace App\Presentation\Helpers\Validation\Validators\ValidationExceptions;

use App\Presentation\Helpers\Validation\ValidationError;

class ErrorBag extends ValidationError
{
    public $message = '';
    private array $errors = [];

    public function push(ValidationError $error)
    {
        $this->errors[] = $error;
        $this->message .= $error->getMessage();
    }

    public function hasErrors(): bool
    {
        return count($this->errors);
    }
}