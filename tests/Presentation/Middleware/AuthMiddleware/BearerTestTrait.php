<?php

namespace Tests\Presentation\Middleware\AuthMiddleware;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

/**
 * Tests only methods based on refresh token.
 */
trait BearerTestTrait
{
    public function testShouldPassWhenJwtIsProvided()
    {
        self::createDatabase();

        $account = new Account(email: 'mail.com', username: 'user', password: 'pass');
        $repository = $this->getContainer()->get(AccountRepository::class);
        $repository->insert($account);

        $tokenCreator = new BodyTokenCreator($account);
        $token = $tokenCreator->createToken($_ENV['JWT_SECRET']);

        $request = $this->createRequestWithAuthentication($token);

        $response = $this->app->handle($request);

        assertNotNull($response);
        assertSame(200, $response->getStatusCode());

        self::truncateDatabase();
    }
}
