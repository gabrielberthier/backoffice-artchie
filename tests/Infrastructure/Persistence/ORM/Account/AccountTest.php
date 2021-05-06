<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use DI\Container;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AccountTest extends TestCase
{
    public function setUp(): void
    {
        $this->app = $this->getAppInstance();
    }

    public function testShouldInsertAccount()
    {
        /** @var Container $container */
        $container = $this->getContainer();
        /** @var AccountRepository */
        $repository = $container->get(AccountRepository::class);
        $account = new Account(email: 'mail.com', username: 'user', password: 'pass');
        $repository->insert($account);
    }
}
