<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Domain\Repositories\AccountRepository;
use App\Presentation\Handlers\RefreshTokenHandler;
use App\Presentation\Middleware\JWTAuthMiddleware;
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

    private function createMockRequest(): ServerRequestInterface
    {
        return $this->createRequest('GET', '/api/test-auth');
    }
}
