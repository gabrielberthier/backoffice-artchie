<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Data\Entities\Doctrine\DoctrineSignatureToken;
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
            $oldToken = $this->findWithMuseumId($token->museum);
            if ($oldToken instanceof DoctrineSignatureToken) {
                $this->em->remove($oldToken);
                $this->em->flush();
            }

            $newToken = new DoctrineSignatureToken();

            $this->em->persist($newToken->fromModel($token));
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException) {
            throw new DuplicatedTokenException();
        }
    }

    public function findFromMuseum(Museum $museum): ?SignatureToken
    {
        $dbToken = $this->findWithMuseumId($museum);

        return $dbToken?->toModel();
    }

    private function findWithMuseumId(Museum $museum): ?DoctrineSignatureToken
    {
        $id = $museum->id;
        if ($id) {
            return $this->em->getRepository(DoctrineSignatureToken::class)->findOneBy(['museum' => $id]);
        }

        return null;
    }
}
