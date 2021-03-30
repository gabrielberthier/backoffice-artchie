<?php

namespace Tests\Traits\App;

use DI\Container;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\App;

/**
 * App Test Trait.
 */
trait AppTestTrait
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var App
     */
    protected $app;

    /**
     * Create a JSON request.
     *
     * @param string              $method The HTTP method
     * @param string|UriInterface $uri    The URI
     * @param null|array          $data   The json data
     */
    protected function createJsonRequest(
        string $method,
        $uri,
        array $data = null
    ): ServerRequestInterface {
        /**
         * @var RequestInterface
         */
        $request = $this->createRequest($method, $uri);

        if ($data !== null) {
            $request->getBody()->write(json_encode($data));
            $request->getBody()->rewind();
        }

        return $request->withHeader('Content-Type', 'application/json');
    }

    /**
     * Verify that the given array is an exact match for the JSON returned.
     *
     * @param array             $expected The expected array
     * @param ResponseInterface $response The response
     *
     * @throws JsonException
     */
    protected function assertJsonData(array $expected, ResponseInterface $response): void
    {
        $actual = (string) $response->getBody();
        $this->assertSame($expected, (array) json_decode($actual, true, 512, JSON_THROW_ON_ERROR));
    }

    protected function getContainer()
    {
        return $this->app->getContainer();
    }

    protected function autowireContainer($key, $instance)
    {
        /**
         * @var Container
         */
        $container = $this->app->getContainer();
        $container->set($key, $instance);
    }
}
