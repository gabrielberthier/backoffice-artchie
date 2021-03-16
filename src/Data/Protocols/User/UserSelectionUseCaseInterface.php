<?php

namespace App\Data\Protocols\User;

use App\Domain\Models\User;

interface UserSelectionUseCaseInterface {
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;
}