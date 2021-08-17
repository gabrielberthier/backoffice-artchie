<?php

namespace App\Domain\Exceptions\Transaction;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

class NameAlreadyInUse extends HttpSpecializedAdapter
{
    public function wire(ServerRequestInterface $request): HttpException
    {
        $message = 'An error occured while inserting values in transaction. Unique constraint has been violated. Cause: ';
        $message .= $this->message;

        return new HttpBadRequestException($request, $message);
    }
}
