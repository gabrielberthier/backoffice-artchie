<?php

namespace App\Infrastructure\Persistence\SignatureToken;

use App\Domain\Exceptions\Security\DuplicatedTokenException;
use App\Domain\Models\Security\SignatureToken;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class SignatureTokenRepository implements SignatureTokenRepositoryInterface
{
    public function __construct(private EntityManager $em)
    {
    }

    public function save(SignatureToken $token): bool
    {
        try {
            $this->em->persist($token);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicatedTokenException();
        }
    }
}
