<?php
namespace App\Infrastructure\ModelBridge;

use App\Data\Entities\Doctrine\DoctrineSignatureToken;
use App\Domain\Models\Security\SignatureToken;
use DateInterval;
use DateTimeImmutable;

class SignatureTokenBridge
{
    public function convertFromModel(SignatureToken $signatureToken): DoctrineSignatureToken
    {
        $token = new DoctrineSignatureToken(
            id: $signatureToken->id,
            signature: $signatureToken->signature,
            privateKey: $signatureToken->privateKey,
            museum: $signatureToken->museum,
        );


        return $token;
    }

    public function toModel(DoctrineSignatureToken $entity): SignatureToken
    {
        $createdAt = DateTimeImmutable::createFromInterface($entity->getCreatedAt());

        return new SignatureToken(
            id: $entity->getId(),
            signature: $entity->getSignature(),
            privateKey: $entity->getPrivateKey(),
            museum: $entity->getMuseum(),
            createdAt: $createdAt,
            updated: $entity->getUpdated(),
            ttl: $createdAt->add(new DateInterval('P6M')),
        );

    }
}