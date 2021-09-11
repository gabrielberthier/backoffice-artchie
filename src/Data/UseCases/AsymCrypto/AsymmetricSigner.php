<?php

namespace App\Data\UseCases\AsymCrypto;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Domain\Exceptions\Museum\MuseumNotFoundException;
use App\Domain\Repositories\MuseumRepository;
use Ramsey\Uuid\UuidInterface;

class AsymmetricSigner implements SignerInterface
{
    public function __construct(private MuseumRepository $museumRepository)
    {
    }

    public function sign(UuidInterface $uuidInterface): string
    {
        $museum = $this->museumRepository->findByUUID($uuidInterface);

        if ($museum) {
            return '';
        }

        throw new MuseumNotFoundException();
    }
}
