<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws \App\Domain\Exceptions\UserNotFoundException
     */
    public function findUserOfId(int $id): User;
}