<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\Markers\Store;

use App\Domain\Dto\Credentials;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Integration test.
 *
 * @internal
 * @coversNothing
 */
class MarkerServiceStoreTest extends TestCase
{

    private \Prophecy\Prophet $prophet;
    private SutTypes $sut;

    protected function setUp(): void
    {
        $this->prophet = new \Prophecy\Prophet;
        /** @var MuseumRepository */
        $museumRepository = $this->mockMuseumRepository();
        /** @var MarkerRepositoryInterface */
        $markerRepository = $this->mockMarkerRepository();
        /** @var EntityManager */
        $em = $this->mockEntityManager();

        $this->sut = new SutTypes($museumRepository, $markerRepository, $em);
    }

    public function makeCredentials()
    {
        return new Credentials(access: '@mail.com', password: 'password');
    }

    public function mockEntityManager()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $em->method('beginTransaction')->willReturn(0);

        $conn = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $em->method('getConnection')->willReturn($conn);

        return $em;
    }

    /** @return MockObject */
    public function mockMuseumRepository()
    {
        return $this->getMockBuilder(MuseumRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @return MockObject */
    public function mockMarkerRepository()
    {
        return $this->getMockBuilder(MarkerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldPassWhenServiceIsCalled()
    {
        $service = $this->sut->service;
        /**
         * @var MockObject
         */
        $mock = $this->sut->museumRepository;
        $mock->expects($this->once())
            ->method('findByID')
            ->with(13)
            ->willReturn(new Museum(1, email: 'email', name: 'name'));

        $service->insert(
            13,
            new Marker(
                null,
                null,
                "name",
                "text",
                "title",
            )
        );
    }
}