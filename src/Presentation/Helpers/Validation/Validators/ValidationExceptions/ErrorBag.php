<?php

namespace App\Presentation\Helpers\Validation\Validators\ValidationExceptions;

use App\Presentation\Helpers\Validation\ValidationError;

class ErrorBag extends ValidationError
{
    private $messages = [];
    private array $errors = [];

    public function push(ValidationError $error)
    {
        $this->errors[] = $error;
        $this->messages[] = "[{$error->getField()}]: {$error->getMessage()}";
        $this->message = join("\n", $this->messages);
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
