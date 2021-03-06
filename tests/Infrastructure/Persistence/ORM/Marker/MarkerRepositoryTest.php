<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\ORM\Marker;

use App\Domain\Models\Marker;
use App\Domain\Models\MarkerAsset;
use App\Domain\Models\PlacementObject;
use App\Domain\Repositories\MarkerRepositoryInterface;
use Doctrine\ORM\EntityManager;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class MarkerRepositoryTest extends TestCase
{
    private MarkerRepositoryInterface $repository;
    private EntityManager $entityManager;

    public static function setUpBeforeClass(): void
    {
        self::createDatabase();
    }

    public static function tearDownAfterClass(): void
    {
        self::truncateDatabase();
    }

    public function setUp(): void
    {
        $this->getAppInstance();
        $container = $this->getContainer();
        $this->repository = $container->get(MarkerRepositoryInterface::class);
        $this->entityManager = $container->get(EntityManager::class);
    }

    protected function tearDown(): void
    {
        $entityManager = $this->entityManager;
        $collection = $entityManager->getRepository(Marker::class)->findAll();
        foreach ($collection as $c) {
            $entityManager->remove($c);
        }
        $entityManager->flush();
        $entityManager->clear();
    }

    public function testShouldInsertMarker()
    {
        $marker = new Marker();
        $marker->setName('Boy with apple.png');
        $marker->setText('The boy with an apple is a famous portrait of a boy with an apple');
        $marker->setTitle('Boy with an apple');

        $this->repository->insert($marker);
        $total = $this->getTotalCount();

        assertSame($total, 1);
    }

    public function testShouldRetrieveMarker()
    {
        $marker = new Marker();
        $marker->setName('Boy with apple.png');
        $marker->setText('The boy with an apple is a famous portrait of a boy with an apple');
        $marker->setTitle('Boy with an apple');

        $this->repository->insert($marker);

        $new_marker = $this->entityManager->getRepository(Marker::class)->findBy([], ['id' => 'DESC'], 1, 0)[0];

        //print_r($account);

        assertInstanceOf(Marker::class, $new_marker);
    }

    public function testShouldInsertMarkerWithAsset()
    {
        $marker = new Marker();
        $marker->setName('Boy with apple.png');
        $marker->setText('The boy with an apple is a famous portrait of a boy with an apple');
        $marker->setTitle('Boy with an apple');

        $asset = new MarkerAsset();
        $asset->setFileName('boyapple.png');
        $asset->setPath('domain/path/boyaple.png');
        $asset->setUrl('www.name.com');
        $asset->setMediaType('file/png');

        $marker->setAsset($asset);

        $this->repository->insert($marker);

        $new_marker = $this->entityManager->getRepository(Marker::class)->findBy([], ['id' => 'DESC'], 1, 0)[0];

        //print_r($account);

        $new_asset = $new_marker->getAsset();

        assertInstanceOf(Marker::class, $new_marker);
        assertInstanceOf(MarkerAsset::class, $new_asset);
    }

    public function testShouldInsertMarkerWithResources()
    {
        $marker = new Marker();
        $marker->setName('Boy with apple.png');
        $marker->setText('The boy with an apple is a famous portrait of a boy with an apple');
        $marker->setTitle('Boy with an apple');

        $placementObject = new PlacementObject();

        $placementObject->setName('Object to place over pictyre');

        $marker->addResource($placementObject);

        $this->repository->insert($marker);

        /** @var Marker */
        $new_marker = $this->entityManager->getRepository(Marker::class)->findBy([], ['id' => 'DESC'], 1, 0)[0];

        //print_r($account);

        $resources = $new_marker->getResources();

        assertSame($resources->count(), 1);
        $resource = $resources->get(0);
        assertInstanceOf(PlacementObject::class, $resource);
    }

    private function getTotalCount(): int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select($qb->expr()->count('u'))
            ->from(Marker::class, 'u')
            // ->where('u.type = ?1')
            // ->setParameter(1, 'employee')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}
