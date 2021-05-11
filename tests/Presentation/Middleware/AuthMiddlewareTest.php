<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Domain\Repositories\AccountRepository;
use App\Presentation\Handlers\OnJwtErrorHandler;
use App\Presentation\Middleware\JWTAuthMiddleware;
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

    private $sut;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->prophet = new Prophet();
        $container = $this->getContainer();
        $jwtErrorHandler = new OnJwtErrorHandler($container->get(AccountRepository::class));
        $this->sut = new JWTAuthMiddleware($container->get(LoggerInterface::class), $jwtErrorHandler);
    }

    public function shouldCallErrorOnJWTErrorHandlerWhenNoRefreshTokenIsProvided()
    {
    }

    private function createMockRequest(): ServerRequestInterface
    {
        return $this->createRequest('GET', '/api');
    }
}
