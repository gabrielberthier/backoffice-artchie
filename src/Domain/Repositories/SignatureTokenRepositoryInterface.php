<?php

namespace App\Domain\Repositories;

use App\Domain\Exceptions\Security\DuplicatedTokenException;
use App\Domain\Models\Security\SignatureToken;

interface SignatureTokenRepositoryInterface
{
    /**
     * Inserts a new token in the database.
     *
     * @throws DuplicatedTokenException
     */
    public function save(SignatureToken $token): bool;
}
