<?php

namespace Tests\Traits\App;

use JsonException;
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
}
