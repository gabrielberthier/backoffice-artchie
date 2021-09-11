<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\Museum;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;

class MuseumNotFoundException extends DomainRecordNotFoundException
{
    public function wire(ServerRequestInterface $request): HttpException
    {
        $message = 'The museum you requested does not exist.';

        return new HttpNotFoundException($request, $message);
    }
}
