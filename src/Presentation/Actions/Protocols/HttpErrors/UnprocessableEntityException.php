<?php

namespace App\Presentation\Actions\Protocols\HttpErrors;

use Slim\Exception\HttpSpecializedException;
use Throwable;

class UnprocessableEntityException extends HttpSpecializedException
{
    protected $code = 422;
    protected ?Throwable $previous = null;
    protected $title = '422 Unprocessable Entity';
    protected $description = 'The request was well-formed but was unable to be followed due to semantic errors.';
}
