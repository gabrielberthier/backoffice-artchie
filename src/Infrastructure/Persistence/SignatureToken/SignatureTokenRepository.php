<?php

namespace App\Infrastructure\Persistence\SignatureToken;

use App\Domain\Exceptions\Security\DuplicatedTokenException;
use App\Domain\Models\Museum;
use App\Domain\Models\Security\SignatureToken;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class SignatureTokenRepository implements SignatureTokenRepositoryInterface, SignatureTokenRetrieverInterface
{
    public function __construct(private EntityManager $em)
    {
    }

    public function save(SignatureToken $token): bool
    {
        try {
            $oldToken = $this->findFromMuseum($token->getMuseum());
            if ($oldToken) {
                $this->em->remove($oldToken);
                $this->em->flush();
            }
            $this->em->persist($token);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicatedTokenException();
        }
    }

    public function findFromMuseum(Museum $museum): ?SignatureToken
    {
        $id = $museum->getId();
        if ($id) {
            return $this->em->getRepository(SignatureToken::class)->findOneBy(['museum' => $id]);
        }
        return null;
    }
}
