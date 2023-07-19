<?php

namespace App\Infrastructure\Persistence\Cycle;

use App\Data\Entities\Cycle\CycleAccount;
use App\Domain\Dto\AccountDto;
use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Models\Enums\AuthTypes;
use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\ModelBridge\AccountBridge;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORM;
use Cycle\ORM\RepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class CycleAccountRepository implements AccountRepository
{
    private EntityManager $em;
    public function __construct(private ORM $orm, private AccountBridge $accountBridge)
    {
        $this->em = new EntityManager($this->orm);
    }

    public function findByAccess(string $access): ?Account
    {
        $findBy = filter_var($access, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $cycleAccount = $this->repository()->findOne([$findBy => $access]);

        return $this->accountBridge->toModel($cycleAccount);
    }

    public function findByMail(string $mail): ?Account
    {
        $cycleAccount = $this->repository()->findOne(['email' => $mail]);

        return $this->accountBridge->toModel($cycleAccount);
    }

    public function findByUUID(string $uuid): ?Account
    {
        $cycleAccount = $this->repository()->findOne(['uuid' => $uuid]);

        return $this->accountBridge->toModel($cycleAccount);
    }

    public function findWithAuthType(string $email, AuthTypes $authType): ?Account
    {
        $cycleAccount = $this->repository()->findOne(
            [
                'email' => $email,
                'authType' => $authType->value
            ]
        );

        return $this->accountBridge->toModel($cycleAccount);
    }

    public function insert(AccountDto $accountDto): Account
    {
        try {
            $account = new CycleAccount();
            $account->setEmail($accountDto->email)
                ->setUsername($accountDto->username)
                ->setAuthType($accountDto->authType->value)
                ->setPassword($accountDto->password);
            $this->em->persist($account);

            $this->em->run();

            return $this->accountBridge->toModel($account);
        } catch (UniqueConstraintViolationException) {
            throw new UserAlreadyRegisteredException();
        }
    }

    private function repository(): RepositoryInterface
    {
        return $this->orm->getRepository(CycleAccount::class);
    }
}