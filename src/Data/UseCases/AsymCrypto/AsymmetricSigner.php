<?php

namespace App\Data\UseCases\AsymCrypto;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Domain\Exceptions\Museum\MuseumNotFoundException;
use App\Domain\Repositories\MuseumRepository;
use Ramsey\Uuid\UuidInterface;

class AsymmetricSigner implements SignerInterface
{
    public function __construct(
        private MuseumRepository $museumRepository,
        private AsymmetricEncrypter $encrypter
    ) {
    }

    public function sign(UuidInterface $uuidInterface): string
    {
        $museum = $this->museumRepository->findByUUID($uuidInterface);

        if ($museum) {
            $this->encrypter->encrypt(json_encode([
                'uuid' => $museum->getUuid()->toString(),
                'museum_name' => $museum->getName(),
            ]));

            return '';
        }

        throw new MuseumNotFoundException();
    }
}
