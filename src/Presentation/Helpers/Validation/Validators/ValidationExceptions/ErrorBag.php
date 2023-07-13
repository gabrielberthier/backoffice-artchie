<?php

namespace App\Presentation\Helpers\Validation\Validators\ValidationExceptions;



class ErrorBag extends ValidationError
{
    private $messages = [];
    
    private array $errors = [];

    public function push(ValidationError $error)
    {
        $this->errors[] = $error;
        $this->messages[] = sprintf('[%s]: %s', $error->getField(), $error->getMessage());
        $this->message = implode(PHP_EOL, $this->messages);
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
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
