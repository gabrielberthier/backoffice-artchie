<?php

namespace App\Domain\Exceptions\Account;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;

class UserAlreadyRegisteredException extends HttpSpecializedAdapter
{
    private string $responsaMessage = 'O nome de usuário ou o email escolhido já foi utilizado';

    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpForbiddenException($request, $this->responsaMessage);
    }
}
