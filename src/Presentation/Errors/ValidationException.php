<?php

namespace App\Presentation\Errors;

use App\Presentation\Actions\Protocols\HttpErrors\UnprocessableEntityException as HttpErrorsUnprocessableEntityException;

final class ValidationException extends HttpErrorsUnprocessableEntityException
{
    public function __construct(
        protected $message,
        private array $errors = [],
    ) {
        $text = 'List of errors: ';
        foreach ($this->errors as $value) {
            $text .= PHP_EOL . $value;
        }
        
        $this->setDescription($text);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
