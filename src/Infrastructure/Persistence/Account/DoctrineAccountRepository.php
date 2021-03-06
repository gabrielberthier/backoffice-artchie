<?php

namespace App\Infrastructure\Persistence\Account;

use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class DoctrineAccountRepository implements AccountRepository
{
    public function __construct(private EntityManager $em)
    {
    }

    public function findByAccess(string $access): ?Account
    {
        $findBy = filter_var($access, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return $this->em->getRepository(Account::class)->findOneBy([$findBy => $access]);
    }

    public function findByMail(string $mail): ?Account
    {
        return $this->em->getRepository(Account::class)->findOneBy(['email' => $mail]);
    }

    public function findByUUID(string $uuid): ?Account
    {
        return $this->em->getRepository(Account::class)->findOneBy(['uuid' => $uuid]);
    }

    public function insert(Account $account): bool
    {
        try {
            $this->em->persist($account);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException) {
            throw new UserAlreadyRegisteredException();
        }
    }
}
