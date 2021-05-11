<?php

namespace Tests\Traits\App;

use DI\Container;
use JsonException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * App Test Trait.
 */
trait AppTestTrait
{
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

    /**
     * Gets the used container.
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
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
