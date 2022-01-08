<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\AsymCrypto;

use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Data\UseCases\AsymCrypto\AsymmetricSigner;
use App\Domain\DTO\Signature;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Uuid\Uuid;
use Tests\Domain\UseCases\AsymCrypto\Signer\AsymmetricEncrypterTestTrait;
use Tests\Domain\UseCases\AsymCrypto\Signer\RepositoryBatteryTestTrait;
use Tests\Domain\UseCases\AsymCrypto\Signer\SignerSutTypes as SutTypes;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SignerTest extends TestCase
{
    use ProphecyTrait;
    use RepositoryBatteryTestTrait;
    use AsymmetricEncrypterTestTrait;

    private SutTypes $sut;

    public function setUp(): void
    {
        /** @var MuseumRepository */
        $repository = $this->createMockRepository();
        /** @var AsymmetricEncrypter */
        $encrypter = $this->createEncrypterMock();
        /** @var SignatureTokenRepositoryInterface */
        $signatureTokenRepository = $this->createTokenRepositoryMock();

        $signer = new AsymmetricSigner($repository, $encrypter, $signatureTokenRepository);

        $this->sut = new SutTypes($signer, $repository, $encrypter, $signatureTokenRepository);
    }

    public function testIfSignatureTokenRepositoryMakesInsertion()
    {
        /** @var MockObject */
        $tokenRepository = $this->sut->signatureTokenRepository;
        /** @var MockObject */
        $encrypter = $this->sut->encrypter;
        /** @var MockObject */
        $museumRepository = $this->sut->repository;

        $encrypter->method('encrypt')->willReturn(new Signature('privKey', 'pubKey', 'signature'));

        $museum = new Museum(email: '', name: '');

        $museumRepository->method('findByUUID')->willReturn($museum);

        $tokenRepository->expects($this->once())->method('save');

        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');

        $this->sut->signer->sign($uuid);
    }

    /**
     * Create a mocked login service.
     *
     * @return MockObject
     */
    private function createTokenRepositoryMock()
    {
        return $this->getMockBuilder(SignatureTokenRepositoryInterface::class)
            ->onlyMethods(['save'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
