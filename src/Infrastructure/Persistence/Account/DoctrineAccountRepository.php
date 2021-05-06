<?php

namespace App\Infrastructure\Persistence\Account;

use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Doctrine\ORM\EntityManager;

class DoctrineAccountRepository implements AccountRepository
{
    public function __construct(private EntityManager $em)
    {
    }

    public function findByMail(string $mail): ?Account
    {
        $this->em->getRepository(Account::class)->findAll();

        return null;
    }

    public function findByUUID(string $uuid): ?Account
    {
        return null;
    }

    public function insert(Account $account): bool
    {
        $this->em->persist($account);
        $this->em->flush();

        return true;
    }
}
