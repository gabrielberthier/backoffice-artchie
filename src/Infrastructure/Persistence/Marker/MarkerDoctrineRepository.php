<?php

namespace App\Infrastructure\Persistence\Marker;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Exceptions\Marker\MarkerNameRepeated;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\SearchResult;
use App\Infrastructure\Persistence\Abstract\AbstractRepository;
use App\Infrastructure\Persistence\Pagination\PaginationService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;

class MarkerDoctrineRepository extends AbstractRepository implements MarkerRepositoryInterface
{

    function entity(): string
    {
        return Marker::class;
    }

    public function add(Marker $marker): bool
    {
        try {
            $this->em->getConnection()->beginTransaction();

            $asset = $marker->getMediaAsset();
            if ($asset) {
                $this->em->persist($asset);
            }
            foreach ($marker->getResources() as $resource) {
                $posedAsset = $resource->getAsset()?->getAsset();
                if ($posedAsset) {
                    $this->em->persist($posedAsset);
                }
            }

            $this->em->persist($marker);
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
        }
    }

    public function findAllByMuseum(int|Museum $museum, bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        $id = $museum;
        if ($museum instanceof Museum) {
            $id = $museum->getId();
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
        $marker = $this->findByID($id);

        if ($marker) {
            try {
                $marker->setText($values['text'] ?? $marker->getText());
                $marker->setName($values['name'] ?? $marker->getName());
                $marker->setTitle($values['title'] ?? $marker->getTitle());
                $marker->setIsActive($values['isActive'] ?? $marker->getIsActive());

                $this->em->flush();
            } catch (UniqueConstraintViolationException) {
                throw new MarkerNameRepeated();
            }
        }

        return $marker;
    }


    public function findByID(int $id): ?Marker
    {
        return parent::findByID($id);
    }
}
