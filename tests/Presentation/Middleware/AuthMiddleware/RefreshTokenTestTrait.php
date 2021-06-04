<?php

namespace Tests\Presentation\Middleware\AuthMiddleware;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Cryptography\CookieTokenCreator;
use App\Presentation\Handlers\RefreshTokenHandler;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

/**
 * Tests only methods based on refresh token.
 */
trait RefreshTokenTestTrait
{
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
        $app = $this->createAppInstance();
        self::createDatabase();
        $this->setUpErrorHandler($app);

        $account = new Account(email: 'mail.com', username: 'user', password: 'pass');
        $repository = $this->getContainer()->get(AccountRepository::class);
        $repository->insert($account);

        $cookieCreator = new CookieTokenCreator($account->getUuid());
        $cookie = $cookieCreator->createToken($_ENV['JWT_SECRET_COOKIE']);

        $request = $this->createMockRequest();
        $request = $request->withCookieParams([REFRESH_TOKEN => $cookie]);

        $response = $app->handle($request);

        assertNotNull($response);
        assertSame(201, $response->getStatusCode());
        self::truncateDatabase();
    }
}
