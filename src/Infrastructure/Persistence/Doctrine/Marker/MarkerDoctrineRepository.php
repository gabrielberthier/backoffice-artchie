<?php

namespace App\Infrastructure\Persistence\Doctrine\Marker;


use App\Data\Entities\Doctrine\DoctrineMarker;
use App\Domain\Exceptions\Marker\MarkerNameRepeated;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\SearchResult;
use App\Infrastructure\ModelBridge\MarkerBridge;
use App\Infrastructure\Persistence\Abstraction\AbstractRepository;
use App\Infrastructure\Persistence\Abstraction\DoctrineAbstractCrud;
use App\Infrastructure\Persistence\Pagination\PaginationService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;

/**
 * @extends AbstractRepository<\App\Domain\Models\Marker\Marker>
 */
class MarkerDoctrineRepository extends DoctrineAbstractCrud implements MarkerRepositoryInterface
{

    function entity(): string
    {
        return DoctrineMarker::class;
    }

    public function add(Marker $marker): bool
    {
        try {
            $bridge = new MarkerBridge();
            $doctrineMarker = $bridge->convertFromModel($marker);

            $this->em->getConnection()->beginTransaction();

            $asset = $doctrineMarker->getAsset();

            if ($asset) {
                $this->em->persist($asset);
            }

            foreach ($doctrineMarker->getResources() as $resource) {
                $posedAsset = $resource->getAsset()?->getAsset();
                if ($posedAsset) {
                    $this->em->persist($posedAsset);
                }
            }

            $this->em->persist($doctrineMarker);
            $this->em->flush();

            $this->em->getConnection()->commit();

            return true;
        } catch (UniqueConstraintViolationException $e) {
            $exception = new MarkerNameRepeated();
            $violationMessage = explode('1062', $e->getMessage())[1] ?? $e->getMessage();
            $exception->addViolationQueue($violationMessage);
            $this->em->getConnection()->rollBack();

            throw $exception;
        } catch (Exception $ex) {
            echo $ex;

            $this->em->getConnection()->rollBack();

            return false;
        }
    }

    public function findAllByMuseum(int|Museum $museum, bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        $id = $museum;
        if ($museum instanceof Museum) {
            $id = $museum->id;
        }

        if ($paginate && $page && $limit) {
            $query = $this->em
                ->createQueryBuilder()
                ->select('m')
                ->from(Marker::class, 'm')
                ->where('m.museum = :museum AND m.isActive = TRUE')
                ->setParameter('museum', $id);

            $pagination = new PaginationService($query);

            return $pagination->paginate($page, $limit);
        }

        $items = $this->findWithConditions(['museum' => $id, 'isActive' => true]);

        return new SearchResult($items);
    }

    public function update(int $id, array $values): ?Marker
    {
        $dM = $this->em->getRepository(DoctrineMarker::class)->find($id);

        if ($dM) {
            try {
                $dMarker = new DoctrineMarker();
                $dMarker->setText($values['text'] ?? $dM->getText());
                $dMarker->setName($values['name'] ?? $dM->getName());
                $dMarker->setTitle($values['title'] ?? $dM->getTitle());
                $dMarker->setIsActive($values['isActive'] ?? $dM->getIsActive());

                $this->em->flush();
            } catch (UniqueConstraintViolationException) {
                throw new MarkerNameRepeated();
            }
        }
    }


    public function findByID(int $id): ?Marker
    {
        $bridge = new MarkerBridge();
        return $bridge->toModel(parent::findByID($id));
    }

}