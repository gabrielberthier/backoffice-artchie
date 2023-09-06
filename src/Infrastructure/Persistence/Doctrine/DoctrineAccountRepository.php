<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Data\Entities\Doctrine\DoctrineAccount;
use App\Domain\Dto\AccountDto;
use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Models\Enums\AuthTypes;
use App\Domain\OptionalApi\Result\Ok;
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

        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy([$findBy => $access]);

        return $doctrineAccount->toModel();
    }

    public function findByMail(string $mail): ?Account
    {
        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy(['email' => $mail]);

        return $doctrineAccount->toModel();
    }

    public function findByUUID(string $uuid): ?Account
    {
        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy(['uuid' => $uuid]);

        return $doctrineAccount->toModel();
    }

    public function findWithAuthType(string $email, AuthTypes $authType): ?Account
    {
        $doctrineAccount = $this->em->getRepository(DoctrineAccount::class)->findOneBy(
            [
                'email' => $email,
                'authType' => $authType->value
            ]
        );

        return $doctrineAccount->toModel();
    }

    public function insert(AccountDto $accountDto): Account
    {
        try {
            $doctrineAccount = new DoctrineAccount(null, ...$accountDto->getData());
            $this->em->persist($doctrineAccount);
            $this->em->flush();

            return $doctrineAccount->toModel();
        } catch (UniqueConstraintViolationException) {

            throw new UserAlreadyRegisteredException();
        }
    }
}