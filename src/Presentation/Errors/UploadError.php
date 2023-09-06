<?php

namespace App\Presentation\Errors;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Throwable;

class UploadError extends HttpBadRequestException
{
    public function __construct(
        ServerRequestInterface $request,
        ?string $object = null,
        ?Throwable $previous = null
    ) {
        $message = $object ? sprintf('Object %s could not be uploaded', $object) : 'An error occured while uploading';
        parent::__construct($request, $message, $previous);
    }
}