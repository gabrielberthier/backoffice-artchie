<?php

namespace App\Domain\Exceptions\Marker;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

class MarkerNameRepeated extends HttpSpecializedAdapter
{
    private string $responsaMessage = 'O nome de marcador já foi utilizado';

    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpBadRequestException($request, $this->responsaMessage);
    }
}
