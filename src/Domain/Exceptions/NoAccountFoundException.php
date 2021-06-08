<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;

class NoAccountFoundException extends DomainRecordNotFoundException
{
    public function wire(ServerRequestInterface $request): HttpException
    {
        $message = 'The account you requested does not exist.';

        return new HttpNotFoundException($request, $message);
    }
}
