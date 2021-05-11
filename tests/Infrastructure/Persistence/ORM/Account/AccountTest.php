<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AccountTest extends TestCase
{
    private AccountRepository $repository;

    public function setUp(): void
    {
        $this->getAppInstance();
        /** @var Container $container */
        $container = $this->getContainer();
        // @var AccountRepository
        $this->repository = $container->get(AccountRepository::class);
    }

    protected function tearDown(): void
    {
        /** @var Container $container */
        $container = $this->getContainer();
        /** @var EntityManager */
        $entityManager = $container->get(EntityManager::class);
        $collection = $entityManager->getRepository(Account::class)->findAll();
        foreach ($collection as $c) {
            $entityManager->remove($c);
        }
        $entityManager->flush();
        $entityManager->clear();
    }

    public function testShouldInsertAccount()
    {
        $account = new Account(email: 'mail.com', username: 'user', password: 'pass');
        $this->repository->insert($account);
    }
}
