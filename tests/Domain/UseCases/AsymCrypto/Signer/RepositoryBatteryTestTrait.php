<?php

namespace Tests\Domain\UseCases\AsymCrypto\Signer;

use App\Domain\DTO\Signature;
use App\Domain\Exceptions\Museum\MuseumNotFoundException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use Ramsey\Uuid\Uuid;

trait RepositoryBatteryTestTrait
{
    public function testShouldCallRepositoryWithCorrectValues()
    {
        /** @var MockObject */
        $repository = $this->sut->repository;

        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');

        $repository->expects($this->once())->method('findByUUID')->with($uuid)->willReturn(new Museum(email: '', name: ''));
        /** @var MockObject */
        $encrypter = $this->sut->encrypter;
        $encrypter->method('encrypt')->willReturn(new Signature('privKey', 'pubKey', 'signature'));

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
}
