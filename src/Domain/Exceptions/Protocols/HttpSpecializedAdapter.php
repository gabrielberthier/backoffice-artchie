<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\Protocols;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;

abstract class HttpSpecializedAdapter extends DomainException
{
    abstract public function wire(ServerRequestInterface $request): HttpException;
}
