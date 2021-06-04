<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Domain\Repositories\AccountRepository;
use App\Presentation\Handlers\RefreshTokenHandler;
use App\Presentation\Middleware\JWTAuthMiddleware;
use DI\Container;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophet;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AuthMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    private $prophet;

    private JWTAuthMiddleware $sut;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->prophet = new Prophet();
        $container = $this->getContainer();
        $jwtErrorHandler = new RefreshTokenHandler($container->get(AccountRepository::class));
        $this->sut = new JWTAuthMiddleware($container->get(LoggerInterface::class), $jwtErrorHandler);
    }

    public function testShouldCallErrorOnJWTErrorHandlerWhenNoRefreshTokenIsProvided()
    {
        $app = $this->app;
        $this->setUpErrorHandler($app);
        $response = $app->handle($this->createMockRequest());

        assertNotNull($response);
        assertSame(401, $response->getStatusCode());
    }

    public function testShouldInterceptHttpCookieRefresh()
    {
        $app = $this->createAppInstance();

        $this->setUpErrorHandler($app);
        $request = $this->createMockRequest();

        $tokenValue = 'any_value';

        $request = $request->withCookieParams([REFRESH_TOKEN => $tokenValue]);

        $mockJwtRefreshHandler = $this->getMockBuilder(RefreshTokenHandler::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $mockJwtRefreshHandler->expects($this->once())
            ->method('setRefreshToken')
            ->with($tokenValue)
        ;

        /**
         * @var Container
         */
        $container = $this->getContainer();

        $container->set(RefreshTokenHandler::class, $mockJwtRefreshHandler);

        $response = $app->handle($request);

        assertNotNull($response);
    }

    public function testShouldReturnNewJtwCaseRefreshIsSet()
    {
        $app = $this->app;
        $this->setUpErrorHandler($app);
        $response = $app->handle($this->createMockRequest());

        assertNotNull($response);
        assertSame(401, $response->getStatusCode());
    }

    private function createMockRequest(): ServerRequestInterface
    {
        return $this->createRequest('GET', '/api/test-auth');
    }
}
