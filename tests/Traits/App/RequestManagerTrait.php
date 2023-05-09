<?php

namespace Tests\Traits\App;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;
use Tests\Builders\Request\RequestBuilder;

trait RequestManagerTrait
{
    protected function createRequest(
        string $method,
        string $path,
        array $headers = [
            'HTTP_ACCEPT' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        array $serverParams = [],
        array $cookies = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new Request($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    protected function constructPostRequest(
        array|object $data,
        string $method,
        string $path,
        array $headers = null,
        array $serverParams = null,
        array $cookies = null
    ): ServerRequestInterface {
        if ((!$method) || !$path) {
            throw new Exception('Unable to create request');
        }
        $requestBuilder = new RequestBuilder($method, $path);
        if ($headers) {
            $requestBuilder->withHeaders($headers);
        }
        if ($serverParams) {
            $requestBuilder->withServerParam($serverParams);
        }
        if ($cookies) {
            $requestBuilder->withCookies($cookies);
        }

        $request = $requestBuilder->build();
        $this->setRequestParsedBody($request, $data);

        return $request;
    }

    protected function setRequestParsedBody(ServerRequestInterface $request, array|object $data): ServerRequestInterface
    {
        $encodedData = json_encode($data);
        $request->getBody()->write($encodedData);
        $request->getBody()->rewind();
        $requestParsedData = json_decode($encodedData, true);
        return ($request->withParsedBody($requestParsedData));
    }

    /**
     * Create a JSON request.
     *
     * @param string              $method The HTTP method
     * @param string|\Psr\Http\Message\UriInterface $uri    The URI
     * @param null|array          $data   The json data
     */
    protected function createJsonRequest(
        string $method,
        $uri,
        array|object $data = null
    ): ServerRequestInterface {
        /**
         * @var RequestInterface
         */
        $request = $this->createRequest($method, $uri);

        if ($data !== null) {
            $request = $this->setRequestParsedBody($request, $data);
        }

        return $request->withHeader('Content-Type', 'application/json');
    }
}