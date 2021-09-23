<?php

namespace App\Presentation\Errors;

use RuntimeException;
use Throwable;

class ServerError extends RuntimeException
{
    public $message;

    public function __construct(
        protected $code = 500,
        protected Throwable $error,
        protected ?Throwable $previous = null
    ) {
        $this->message = $error->getMessage();
    }
}
