<?php

namespace App\Domain\Exceptions\Museum;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;

class MuseumAlreadyRegisteredException extends HttpSpecializedAdapter
{
    private string $responsaMessage = 'O nome de museu ou o email escolhido já foi utilizado';

    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpForbiddenException($request, $this->responsaMessage);
    }
}
