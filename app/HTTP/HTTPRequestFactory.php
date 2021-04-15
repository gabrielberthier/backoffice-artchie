<?php

namespace Core\HTTP;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;

class HTTPRequestFactory
{
    // Create Request object from globals
    public function createRequest(): ServerRequestInterface
    {
        $serverRequestCreator = ServerRequestCreatorFactory::create();

        return $serverRequestCreator->createServerRequestFromGlobals();
    }
}
