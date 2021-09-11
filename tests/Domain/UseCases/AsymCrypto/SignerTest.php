<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\AsymCrypto;

use App\Data\Protocols\AsymCrypto\SignerInterface;
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
    public SignerInterface $service;
}

/**
 * @internal
 * @coversNothing
 */
class SignerTest extends TestCase
{
    use ProphecyTrait;

    private SignerInterface $sut;

    public function setUp(): void
    {
        /** @var MuseumRepository */
        $repository = $this->createMockRepository();
        $this->sut = new AsymmetricSigner($repository);
    }

    public function testShouldCallRepositoryWithCorrectValues()
    {
        $repository = $this->createMockRepository();
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $repository->expects($this->once())->method('findByUUID')->with($uuid)->willReturn(new Museum(email: '', name: ''));
        /**
         * @var MuseumRepository
         */
        $repo = $repository;
        $this->sut = new AsymmetricSigner($repo);

        $response = $this->sut->sign($uuid);

        $this->assertTrue(is_string($response));
    }

    public function testShouldThrowNotFoundWhenNoMuseumIsFound()
    {
        $this->expectException(MuseumNotFoundException::class);

        $repository = $this->createMockRepository();
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $repository->expects($this->once())->method('findByUUID')->with($uuid)->willReturn(null);
        /**
         * @var MuseumRepository
         */
        $repo = $repository;
        $this->sut = new AsymmetricSigner($repo);

        $this->sut->sign($uuid);
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
