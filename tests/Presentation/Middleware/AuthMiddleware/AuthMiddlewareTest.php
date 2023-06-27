<?php

declare(strict_types=1);

namespace Tests\Presentation\Middleware\AuthMiddleware;

use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
    use BearerTestTrait;

    public function setUp(): void
    {
        $this->app = $this->createAppInstance();
        $this->app->group('/api', function ($group) {
            $group->get('/test-auth', function (RequestInterface $request, ResponseInterface $response): ResponseInterface {
                $response->getBody()->write('Works');

                return $response;
            });
        });
        $this->setUpErrorHandler($this->app);
    }

    private function createMockRequest(): ServerRequestInterface
    {
        return $this->createRequest('GET', '/api/test-auth');
    }

    private function createRequestWithAuthentication(string $token)
    {
        $bearer = 'Bearer ' . $token;

        return $this->createRequest(
            'GET',
            '/api/test-auth',
            [
                'HTTP_ACCEPT' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $bearer,
            ],
        );
    }
}