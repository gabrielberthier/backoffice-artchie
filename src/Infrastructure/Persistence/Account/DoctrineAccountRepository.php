<?php

namespace App\Infrastructure\Persistence\Account;

use App\Domain\Dto\AccountDto;
use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Models\Enums\AuthTypes;
use App\Domain\Repositories\AccountRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

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

    public function findWithAuthType(string $email, AuthTypes $authType): ?Account{
        return $this->em->getRepository(Account::class)->findOneBy(['email' => $email, 'authType' => $authType->value]);
    }

    public function insert(AccountDto $accountDto): Account
    {
        try {
            $account = new Account(null, ...$accountDto->getData());
            $this->em->persist($account);
            $this->em->flush();

            return $account;
        } catch (UniqueConstraintViolationException) {
            throw new UserAlreadyRegisteredException();
        }
    }
}