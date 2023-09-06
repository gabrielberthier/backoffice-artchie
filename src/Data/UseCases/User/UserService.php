<?php

namespace App\Data\UseCases\User;

use App\Data\Protocols\User\UserUseCaseInterface;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepository;

class UserService implements UserUseCaseInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @throws \App\Domain\Exceptions\UserNotFoundException
     */
    public function findUserOfId(int $id): User
    {
        return $this->userRepository->findUserOfId($id);
    }
}