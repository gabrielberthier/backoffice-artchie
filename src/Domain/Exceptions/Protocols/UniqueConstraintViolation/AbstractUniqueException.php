<?php

namespace App\Domain\Exceptions\Protocols\UniqueConstraintViolation;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

abstract class AbstractUniqueException extends HttpSpecializedAdapter
{
    protected string $responsaMessage;

    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpBadRequestException($request, $this->responsaMessage);
    }

    public function getResponseMessage()
    {
        return $this->responsaMessage;
    }
}
