<?php

namespace App\Presentation\Errors;

use RuntimeException;
use Throwable;

final class ValidationException extends RuntimeException
{
    public function __construct(
        protected $message,
        private array $errors = [],
        protected $code = 422,
        protected ?Throwable $previous = null
    ) {
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
