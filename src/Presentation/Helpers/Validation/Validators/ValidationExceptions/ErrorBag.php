<?php

namespace App\Presentation\Helpers\Validation\Validators\ValidationExceptions;



class ErrorBag extends ValidationError
{
    private $messages = [];
    private array $errors = [];

    public function push(ValidationError $error)
    {
        $this->errors[] = $error;
        $this->messages[] = "[{$error->getField()}]: {$error->getMessage()}";
        $this->message = join("\r", $this->messages);
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Returns all errors
     *
     * @return ValidationError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
