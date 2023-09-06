<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MemoryRepositories;

use App\Domain\Models\Museum;
use App\Domain\Models\Security\SignatureToken;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;


class InMemorySignatureTokenRepository implements
    SignatureTokenRepositoryInterface,
    SignatureTokenRetrieverInterface
{
    /**
     * @var SignatureToken[]
     */
    private readonly array $tokens;

    public function __construct()
    {
        $tokens = [];
    }

    /**
     * Inserts a new token in the database.
     *
     * @throws DuplicatedTokenException
     */
    public function save(SignatureToken $token): bool
    {
        $this->tokens[] = $token;

        return true;
    }

    public function findFromMuseum(Museum $museum): ?SignatureToken
    {
        foreach ($this->tokens as $token) {
            if ($token->museum->id === $museum->id) {
                return $token;
            }
        }

        return null;
    }
}
