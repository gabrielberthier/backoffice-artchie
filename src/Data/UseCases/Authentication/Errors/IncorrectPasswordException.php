<?php

declare(strict_types=1);

namespace App\Data\UseCases\Authentication\Errors;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

class IncorrectPasswordException extends HttpSpecializedAdapter
{
    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpBadRequestException($request, "The passwords don't match");
    }
}
