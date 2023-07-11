<?php

namespace App\Infrastructure\Persistence\Doctrine\Account;

use App\Data\Entities\Doctrine\DoctrineAccount;
use App\Domain\Dto\AccountDto;
use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Models\Enums\AuthTypes;
use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\ModelBridge\AccountBridge;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class DoctrineAccountRepository implements AccountRepository
{
    public function __construct(private EntityManager $em, private AccountBridge $accountBridge)
    {
    }

    public function findByAccess(string $access): ?Account
    {
        $findBy = filter_var($access, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy([$findBy => $access]);

        return $this->accountBridge->toModel($doctrineAccount);
    }

    public function findByMail(string $mail): ?Account
    {
        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy(['email' => $mail]);

        return $this->accountBridge->toModel($doctrineAccount);
    }

    public function findByUUID(string $uuid): ?Account
    {
        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy(['uuid' => $uuid]);

        return $this->accountBridge->toModel($doctrineAccount);
    }

    public function findWithAuthType(string $email, AuthTypes $authType): ?Account
    {
        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy(
            [
                'email' => $email,
                'authType' => $authType->value
            ]
        );

        return $this->accountBridge->toModel($doctrineAccount);
    }

    public function insert(AccountDto $accountDto): Account
    {
        try {
            $doctrineAccount = new DoctrineAccount(null, ...$accountDto->getData());
            $this->em->persist($doctrineAccount);
            $this->em->flush();

            return $this->accountBridge->toModel($doctrineAccount);
        } catch (UniqueConstraintViolationException) {
            throw new UserAlreadyRegisteredException();
        }
    }
}