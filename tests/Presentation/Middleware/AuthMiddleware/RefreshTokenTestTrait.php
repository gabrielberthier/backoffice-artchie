<?php

namespace Tests\Presentation\Middleware\AuthMiddleware;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Cryptography\CookieTokenCreator;
use App\Presentation\Handlers\RefreshTokenHandler;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use Psr\Http\Message\ResponseInterface;

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

        $response = $this->app->handle($request);

        assertNotNull($response);
    }

    public function testShouldReturnNewJtwCaseRefreshIsSet()
    {
        self::createDatabase();

        $account = new Account(email: 'mail.com', username: 'user', password: 'pass');
        $repository = $this->getContainer()->get(AccountRepository::class);
        $repository->insert($account);

        $cookieCreator = new CookieTokenCreator($account->getUuid());
        $cookie = $cookieCreator->createToken($_ENV['JWT_SECRET_COOKIE']);

        $request = $this->createMockRequest();
        $request = $request->withCookieParams([REFRESH_TOKEN => $cookie]);

        /**
         * @var ResponseInterface
         */
        $response = $this->app->handle($request);

        assertTrue($response->hasHeader('X-RENEW-TOKEN'));
        assertNotNull($response);
        assertSame(201, $response->getStatusCode());

        self::truncateDatabase();
    }
}
