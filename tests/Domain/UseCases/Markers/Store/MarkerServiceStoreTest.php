<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\Markers\Store;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Data\UseCases\Markers\MarkerServiceStore;
use App\Domain\Models\DTO\Credentials;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

/**
 * Integration test.
 *
 * @internal
 * @coversNothing
 */
class MarkerServiceStoreTest extends TestCase
{
    use ProphecyTrait;

    private SutTypes $sut;

    protected function setUp(): void
    {
        /** @var MuseumRepository */
        $museumRepository = $this->mockMuseumRepository();
        /** @var MarkerRepositoryInterface */
        $markerRepository = $this->mockMarkerRepository();

        $this->sut = new SutTypes($museumRepository, $markerRepository);
    }

    public function makeCredentials()
    {
        return new Credentials(access: '@mail.com', password: 'password');
    }

    /**
     * @param MuseumRepository          $repository
     * @param MarkerRepositoryInterface $markerRepository
     */
    public function makeService($repository, $markerRepository): MarkerServiceStoreInterface
    {
        return new MarkerServiceStore($repository, $markerRepository);
    }

    /** @return MockObject */
    public function mockMuseumRepository()
    {
        return $this->getMockBuilder(MuseumRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /** @return MockObject */
    public function mockMarkerRepository()
    {
        return $this->getMockBuilder(MarkerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
