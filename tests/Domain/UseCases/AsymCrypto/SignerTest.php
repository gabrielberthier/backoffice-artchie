<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\AsymCrypto;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Data\UseCases\AsymCrypto\AsymmetricSigner;
use App\Domain\Exceptions\Museum\MuseumNotFoundException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class SutTypes
{
    public function __construct(
        public SignerInterface $signer,
        public MuseumRepository $repository,
        public AsymmetricEncrypter $encrypter
    ) {
    }
}

/**
 * @internal
 * @coversNothing
 */
class SignerTest extends TestCase
{
    use ProphecyTrait;

    private SutTypes $sut;

    public function setUp(): void
    {
        /** @var MuseumRepository */
        $repository = $this->createMockRepository();
        /** @var AsymmetricEncrypter */
        $encrypter = $this->createEncrypterMock();
        $signer = new AsymmetricSigner($repository, $encrypter);

        $this->sut = new SutTypes($signer, $repository, $encrypter);
    }

    public function testShouldCallRepositoryWithCorrectValues()
    {
        /** @var MockObject */
        $repository = $this->sut->repository;

        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');

        $repository->expects($this->once())->method('findByUUID')->with($uuid)->willReturn(new Museum(email: '', name: ''));

        $response = $this->sut->signer->sign($uuid);

        $this->assertTrue(is_string($response));
    }

    public function testShouldThrowNotFoundWhenNoMuseumIsFound()
    {
        $this->expectException(MuseumNotFoundException::class);
        /** @var MockObject */
        $repository = $this->sut->repository;
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $repository->expects($this->once())->method('findByUUID')->with($uuid)->willReturn(null);

        $this->sut->signer->sign($uuid);
    }

    public function testIfAsymmetricEncrypterReceivesValues()
    {
        /**
         * @var MockObject
         */
        $encrypter = $this->sut->encrypter;
        /** @var MockObject */
        $repo = $this->sut->repository;
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $repo->method('findByUUID')->willReturn(new Museum(email: '', name: 'test_museum', uuid: $uuid));

        $subject = json_encode(
            [
                'uuid' => '5a4bd710-aab8-4ebc-b65d-0c059a960cfb',
                'museum_name' => 'test_museum',
            ]
        );

        $encrypter->expects($this->once())->method('encrypt')->with($subject);

        $response = $this->sut->signer->sign($uuid);

        $this->assertTrue(is_string($response));
    }

    /**
     * Create a mocked login service.
     *
     * @return MockObject
     */
    private function createMockRepository()
    {
        return $this->getMockBuilder(MuseumRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * Create a mocked login service.
     *
     * @return MockObject
     */
    private function createEncrypterMock()
    {
        return $this->getMockBuilder(AsymmetricEncrypter::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
