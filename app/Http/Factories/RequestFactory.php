<?php

namespace Core\Http\Factories;

use Psr\Http\Message\ServerRequestInterface;

// use Slim\Factory\ServerRequestCreatorFactory;

class RequestFactory
{
    // Create Request object from globals
    public function createRequest(): ServerRequestInterface
    {
        // $serverRequestCreator = ServerRequestCreatorFactory::create();

        // return $serverRequestCreator->createServerRequestFromGlobals();

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
            $psr17Factory,
            // ServerRequestFactory
            $psr17Factory,
            // UriFactory
            $psr17Factory,
            // UploadedFileFactory
            $psr17Factory // StreamFactory
        );

        return $creator->fromGlobals();
    }
}