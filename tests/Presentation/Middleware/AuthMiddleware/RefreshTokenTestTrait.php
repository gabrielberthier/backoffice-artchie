<?php

namespace Tests\Presentation\Middleware\AuthMiddleware;

use App\Presentation\Handlers\RefreshTokenHandler;
use App\Presentation\Helpers\Interceptors\RefreshTokenInterceptor;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

/**
 * Tests only methods based on refresh token.
 */
trait RefreshTokenTestTrait
{
    public function testShouldCallErrorOnJWTErrorHandlerWhenNoRefreshTokenIsProvided()
    {
        $response = $this->app->handle($this->createMockRequest());

        assertNotNull($response);
        assertSame(401, $response->getStatusCode());
    }

    public function testShouldInterceptHttpCookieRefresh()
    {
        $request = $this->createMockRequest();

        $tokenValue = 'any_value';

        $request = $request->withCookieParams([REFRESH_TOKEN => $tokenValue]);

        $mockJwtRefreshHandler = $this->getMockBuilder(RefreshTokenInterceptor::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        /**
         * @var \Psr\Container\ContainerInterface
         */
        $container = $this->getContainer();

        $container->set(RefreshTokenHandler::class, $mockJwtRefreshHandler);

        $response = $this->app->handle($request);

        assertNotNull($response);
    }
}