<?php

namespace App\Data\Protocols\User;

use App\Domain\Models\User;

interface UserSelectionUseCaseInterface
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @throws \App\Domain\Exceptions\UserNotFoundException
     */
    public function findUserOfId(int $id): User;
}