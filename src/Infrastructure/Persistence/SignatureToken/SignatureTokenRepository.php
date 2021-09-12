<?php

namespace App\Infrastructure\Persistence\SignatureToken;

use App\Domain\Exceptions\Security\DuplicatedTokenException;
use App\Domain\Models\Museum;
use App\Domain\Models\Security\SignatureToken;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class SignatureTokenRepository implements SignatureTokenRepositoryInterface, SignatureTokenRetrieverInterface
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

    public function findFromMuseum(Museum $museum): SignatureToken
    {
        $id = $museum;
        if ($museum instanceof Museum) {
            $id = $museum->getId();
        }

        return $this->em->getRepository(SignatureToken::class)->findOneBy(['museum' => $id]);
    }
}
