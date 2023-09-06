<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Orm\Account;

use App\Data\Entities\Doctrine\DoctrineAccount;
use App\Domain\Dto\AccountDto;
use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use function PHPUnit\Framework\assertInstanceOf;
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
        putenv('RR=');
        self::createDatabaseDoctrine();
    }

    public static function tearDownAfterClass(): void
    {
        self::createDatabaseDoctrine();
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
        $collection = $entityManager->getRepository(DoctrineAccount::class)->findAll();
        foreach ($collection as $c) {
            $entityManager->remove($c);
        }
        $entityManager->flush();
        $entityManager->clear();
    }

    /**
     * @group doctrine
     */
    public function testShouldInsertAccount()
    {
        $account = new AccountDto(email: 'mail.com', username: 'user', password: 'pass');
        $this->repository->insert($account);
        $total = $this->getTotalCount();

        $this->assertEquals($total, 1);
    }

    /**
     * @group doctrine
     */
    public function testShouldRetrieveAccount()
    {
        $account = new AccountDto(email: 'mail.com', username: 'user', password: 'pass');
        $this->repository->insert($account);

        $account = $this->repository->findByMail('mail.com');

        //print_r($account);

        assertInstanceOf(Account::class, $account);
    }

    private function getTotalCount(): int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select($qb->expr()->count('u'))
            ->from(DoctrineAccount::class, 'u')
            // ->where('u.type = ?1')
            // ->setParameter(1, 'employee')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}