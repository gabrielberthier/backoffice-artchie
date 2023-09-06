<?php

namespace App\Presentation\Actions\Protocols\HttpErrors;

use Slim\Exception\HttpSpecializedException;
use Throwable;

class UnprocessableEntityException extends HttpSpecializedException
{
    protected $code = 422;
    
    protected ?Throwable $previous = null;
    
    protected string $title = '422 Unprocessable Entity';
    
    protected string $description = 'The request was well-formed but unable to be followed due to semantic errors.';
}