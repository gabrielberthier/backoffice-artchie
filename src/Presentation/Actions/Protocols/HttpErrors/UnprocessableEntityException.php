<?php

use Slim\Exception\HttpSpecializedException;

class UnprocessableEntityException extends HttpSpecializedException
{
    protected $code = 422;
    protected ?Throwable $previous = null;
    protected $title = '422 Unprocessable Entity';
    protected $description = 'The request was well-formed but was unable to be followed due to semantic errors.';
}
