<?php

namespace App\Domain\Exceptions\Museum;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

class MuseumAlreadyRegisteredException extends HttpSpecializedAdapter
{
    private string $responsaMessage = 'O nome de museu ou o email escolhido já foi utilizado';

    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpBadRequestException($request, $this->responsaMessage);
    }
}
