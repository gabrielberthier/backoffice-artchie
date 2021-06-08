<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;

class NoAccountFoundException extends DomainRecordNotFoundException
{
    private $message = 'The account you requested does not exist.';

    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpNotFoundException($request, $this->message);
    }
}
