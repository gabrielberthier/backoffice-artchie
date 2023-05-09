<?php

namespace Core\Http;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;

class RequestFactory
{
    // Create Request object from globals
    public function createRequest(): ServerRequestInterface
    {
        $serverRequestCreator = ServerRequestCreatorFactory::create();

        return $serverRequestCreator->createServerRequestFromGlobals();
    }
}