<?php

namespace Tests\Domain\UseCases\AsymCrypto\Signer;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;

class SignerSutTypes
{
    public function __construct(
        public SignerInterface $signer,
        public MuseumRepository $repository,
        public AsymmetricEncrypter $encrypter,
        public SignatureTokenRepositoryInterface $signatureTokenRepository
    ) {
    }
}
