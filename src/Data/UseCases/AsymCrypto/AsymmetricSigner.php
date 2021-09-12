<?php

namespace App\Data\UseCases\AsymCrypto;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Domain\Exceptions\Museum\MuseumNotFoundException;
use App\Domain\Models\Security\SignatureToken;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class AsymmetricSigner implements SignerInterface
{
    public function __construct(
        private MuseumRepository $museumRepository,
        private AsymmetricEncrypter $encrypter,
        private SignatureTokenRepositoryInterface $tokenRepository
    ) {
    }

    public function sign(UuidInterface $uuidInterface): string
    {
        $museum = $this->museumRepository->findByUUID($uuidInterface);

        if ($museum) {
            $signature = $this->encrypter->encrypt(json_encode([
                'uuid' => $museum->getUuid()->toString(),
                'museum_name' => $museum->getName(),
            ]));

            $signatureToken = new SignatureToken(
                null,
                $signature->signature(),
                $signature->publicKey(),
                $museum
            );

            $this->tokenRepository->save($signatureToken);

            return $this->createTokenResponse(
                $museum->getUuid()->toString(),
                $signature->privateKey()
            );
        }

        throw new MuseumNotFoundException();
    }

    private function createTokenResponse(string $uuid, string $privateKey): string
    {
        $uuid = base64_encode($uuid);
        $privateKey = base64_encode($privateKey);

        return "{$uuid}.{$privateKey}";
    }
}
