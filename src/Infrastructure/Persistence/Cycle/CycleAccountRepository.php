<?php

namespace App\Infrastructure\Persistence\Cycle;

use App\Data\Entities\Cycle\CycleAccount;
use App\Domain\Dto\AccountDto;
use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Models\Enums\AuthTypes;
use App\Domain\Repositories\AccountRepository;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORM;
use Cycle\ORM\RepositoryInterface;
use Cycle\Database\Exception\StatementException\ConstrainException;

class CycleAccountRepository implements AccountRepository
{
    private EntityManager $em;
    public function __construct(private ORM $orm)
    {
        $this->em = new EntityManager($this->orm);
    }

    public function findByAccess(string $access): ?Account
    {
        $findBy = filter_var($access, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $cycleAccount = $this->repository()->findOne([$findBy => $access]);

        return $cycleAccount?->toModel();
    }

    public function findByMail(string $mail): ?Account
    {
        $cycleAccount = $this->repository()->findOne(['email' => $mail]);

        return $cycleAccount?->toModel();
    }

    public function findByUUID(string $uuid): ?Account
    {
        $cycleAccount = $this->repository()->findOne(['uuid' => $uuid]);

        return $cycleAccount?->toModel();
    }

    public function findWithAuthType(string $email, AuthTypes $authType): ?Account
    {
        $cycleAccount = $this->repository()->findOne(
            [
                'email' => $email,
                'authType' => $authType->value
            ]
        );

        return $cycleAccount?->toModel();
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

            return $account?->toModel();
        } catch (ConstrainException) {
            throw new UserAlreadyRegisteredException();
        }
    }

    /**
     * @return RepositoryInterface<CycleAccount>
     */
    private function repository(): RepositoryInterface
    {
        return $this->orm->getRepository(CycleAccount::class);
    }
}
