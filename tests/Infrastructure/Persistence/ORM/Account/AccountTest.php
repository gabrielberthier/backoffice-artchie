<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Doctrine\ORM\EntityManager;
use function PHPUnit\Framework\assertSame;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AccountTest extends TestCase
{
    private AccountRepository $repository;
    private EntityManager $entityManager;

    public static function setUpBeforeClass(): void
    {
        self::createDatabase();
    }

    public static function tearDownAfterClass(): void
    {
        self::truncateDatabase();
    }

    public function setUp(): void
    {
        $this->getAppInstance();
        $container = $this->getContainer();
        $this->repository = $container->get(AccountRepository::class);
        $this->entityManager = $container->get(EntityManager::class);
    }

    protected function tearDown(): void
    {
        $entityManager = $this->entityManager;
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
        $total = $this->getTotalCount();

        assertSame($total, 1);
    }

    private function getTotalCount(): int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select($qb->expr()->count('u'))
            ->from(Account::class, 'u')
            // ->where('u.type = ?1')
            // ->setParameter(1, 'employee')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}
