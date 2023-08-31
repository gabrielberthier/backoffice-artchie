<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Orm\Marker;

use App\Data\Entities\Doctrine\DoctrineMarker;
use App\Data\Entities\Doctrine\DoctrineMarkerAsset;
use App\Data\Entities\Doctrine\DoctrinePlacementObject;
use App\Domain\Models\Assets\PictureAsset;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\PlacementObject\PlacementObject;
use App\Domain\Repositories\MarkerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use function PHPUnit\Framework\assertInstanceOf;
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
        putenv('RR=');
        self::createDatabaseDoctrine();
    }

    public static function tearDownAfterClass(): void
    {
        self::createDatabaseDoctrine();
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
        $collection = $entityManager->getRepository(DoctrineMarker::class)->findAll();
        foreach ($collection as $c) {
            $entityManager->remove($c);
        }
        $entityManager->flush();
        $entityManager->clear();
    }

    /**
     * @group doctrine
     */
    public function testShouldInsertMarker()
    {
        $marker = new Marker(
            null,
            null,
            'Boy with apple.png',
            'The boy with an apple is a famous portrait of a boy with an apple',
            'Boy with an apple'
        );

        $this->repository->add($marker);
        $total = $this->getTotalCount();

        $this->assertEquals($total, 1);
    }

    /**
     * @group doctrine
     */
    public function testShouldRetrieveMarker()
    {
        $marker = new Marker(
            null,
            null,
            'Boy with apple.png',
            'The boy with an apple is a famous portrait of a boy with an apple',
            'Boy with an apple'
        );

        $this->repository->add($marker);

        $new_marker = $this->entityManager->getRepository(DoctrineMarker::class)->findAll()[0];

        //print_r($account);

        assertInstanceOf(DoctrineMarker::class, $new_marker);
    }

    /**
     * @group doctrine
     */
    public function testShouldInsertMarkerWithAsset()
    {
        $asset = new PictureAsset();
        $asset->setFileName('boyapple.png');
        $asset->setPath('domain/path/boyaple.png');
        $asset->setUrl('www.name.com');
        $asset->setOriginalName('boyapp.png');
        $asset->setMimeType('file/png');
        $marker = new Marker(
            null,
            null,
            'Boy with apple.png',
            'The boy with an apple is a famous portrait of a boy with an apple',
            'Boy with an apple',
            $asset
        );

        $this->repository->add($marker);

        $new_marker = $this->entityManager->getRepository(DoctrineMarker::class)->findBy([], ['id' => 'DESC'], 1, 0)[0];

        //print_r($account);

        $new_asset = $new_marker->getAsset();

        assertInstanceOf(DoctrineMarker::class, $new_marker);
        assertInstanceOf(DoctrineMarkerAsset::class, $new_asset);
    }

    /**
     * @group doctrine
     */
    public function testShouldInsertMarkerWithResources()
    {
        $marker = new Marker(
            null,
            null,
            'Boy with apple.png',
            'The boy with an apple is a famous portrait of a boy with an apple',
            'Boy with an apple'
        );

        $placementObject = new PlacementObject(null, 'Object to place over pictyre', null);

        $marker->addResource($placementObject);

        $this->repository->add($marker);

        /** @var DoctrineMarker */
        $new_marker = $this->entityManager->getRepository(DoctrineMarker::class)->findBy([], ['id' => 'DESC'], 1, 0)[0];

        //print_r($account);

        $resources = $new_marker->getResources();

        $this->assertEquals($resources->count(), 1);
        $resource = $resources->get(0);
        assertInstanceOf(DoctrinePlacementObject::class, $resource);
    }

    private function getTotalCount(): int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select($qb->expr()->count('u'))
            ->from(DoctrineMarker::class, 'u')
            // ->where('u.type = ?1')
            // ->setParameter(1, 'employee')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}