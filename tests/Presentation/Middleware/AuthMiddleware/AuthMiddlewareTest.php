<?php

declare(strict_types=1);

namespace Tests\Presentation\Middleware\AuthMiddleware;

use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AuthMiddlewareTest extends TestCase
{
    use ProphecyTrait;
    use RefreshTokenTestTrait;

    private function createMockRequest(): ServerRequestInterface
    {
        return $this->createRequest('GET', '/api/test-auth');
    }
}
