<?php
namespace App\Data\UseCases\SocialLogin\Errors;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpInternalServerErrorException;


class CantGetUserInformationException extends HttpSpecializedAdapter
{
    public function wire(ServerRequestInterface $request): HttpException
    {
        return new HttpInternalServerErrorException($request, "Could not request user's information");
    }
}