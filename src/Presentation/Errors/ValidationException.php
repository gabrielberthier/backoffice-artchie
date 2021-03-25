<?php

namespace App\Presentation\Errors;

use UnprocessableEntityException;

final class ValidationException extends UnprocessableEntityException
{
    public function __construct(
        protected $message,
        private array $errors = [],
    ) {
        $text = 'List of errors: ';
        foreach ($this->errors as $value) {
            $text .= "\n{$value}";
        }
        $this->setDescription($text);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
