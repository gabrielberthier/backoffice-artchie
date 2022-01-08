<?php

namespace App\Domain\Exceptions\Transaction;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

class InstanceNotFoundException extends HttpSpecializedAdapter
{
    public function __construct(private string $object)
    {
    }

    public function wire(ServerRequestInterface $request): HttpException
    {
        $message = "The requested {$this->object} does not exist";
        $message .= $this->message;

        return new HttpBadRequestException($request, $message);
    }
}
