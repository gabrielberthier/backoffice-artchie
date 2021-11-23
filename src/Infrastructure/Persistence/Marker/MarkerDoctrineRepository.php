<?php

namespace App\Infrastructure\Persistence\Marker;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Exceptions\Marker\MarkerNameRepeated;
use App\Domain\Models\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\SearchResult;
use App\Infrastructure\Persistence\Pagination\PaginationService;
use App\Infrastructure\Persistence\PersistenceUtils\ItemsRetriever;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class MarkerDoctrineRepository implements MarkerRepositoryInterface
{
    public function __construct(private EntityManager $em, private ItemsRetriever $itemsRetriever)
    {
    }

    public function insert(ModelInterface $model): bool
    {
        try {
            $this->em->persist($model);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException $e) {
            $exception = new MarkerNameRepeated();
            $violationMessage = explode('1062', $e->getMessage())[1] ?? $e->getMessage();
            $exception->addViolationQueue($violationMessage);

            throw $exception;
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
                ->where('m.museum = :museum')
                ->setParameter('museum', $id)
            ;

            $pagination = new PaginationService($query);

            return $pagination->paginate($page, $limit);
        }

        $items = $this->em->getRepository(Marker::class)->findBy(['museum' => $id]);

        return new SearchResult($items);
    }

    public function findAll(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        return $this->itemsRetriever->findAll(Marker::class, $paginate, $page, $limit);
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

    public function delete(ModelInterface|int $id): ?Marker
    {
        if (is_int($id)) {
            $id = $this->findByID($id);
        }
        if ($id) {
            $this->em->remove($id);
            $this->em->flush();
        }

        return $id;
    }

    public function findByID(int $id): ?Marker
    {
        return $this->em->find(Marker::class, $id);
    }

    public function findByKey(string $key, mixed $value): ?Marker
    {
        return $this->em->getRepository(Marker::class)->findOneBy([$key => $value]);
    }
}
