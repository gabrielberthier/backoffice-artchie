<?php

namespace App\Infrastructure\Persistence\SignatureToken;

use App\Domain\Exceptions\Security\DuplicatedTokenException;
use App\Domain\Models\Museum;
use App\Domain\Models\Security\DoctrineSignatureToken;
use App\Domain\Models\Security\SignatureToken;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use App\Infrastructure\ModelBridge\SignatureTokenBridge;
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
            if ($oldToken) {
                $this->em->remove($oldToken);
                $this->em->flush();
            }
            $tokenBridge = new SignatureTokenBridge();
            $this->em->persist($tokenBridge->convertFromModel($token));
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicatedTokenException();
        }
    }

    public function findFromMuseum(Museum $museum): ?SignatureToken
    {
        $tokenBridge = new SignatureTokenBridge();
        $dbToken = $this->findWithMuseumId($museum);
        return is_null($dbToken) ? null : $tokenBridge->toModel($dbToken);
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