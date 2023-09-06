<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MemoryRepositories;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\SearchResult;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class InMemoryMarkerRepository implements MarkerRepositoryInterface
{
    /** @var Collection<Marker> */
    private readonly Collection $markers;

    public function __construct()
    {
        $this->markers = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return $this->markers->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function findByID(int $id): ?Marker
    {
        return $this->markers->get($id);
    }

    public function add(Marker $model): bool
    {
        $this->markers->add($model);

        return true;
    }

    /**
     * @var Marker[]
     *
     * @param mixed $page
     * @param mixed $limit
     */
    public function findAllByMuseum(int|Museum $museum, bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        if ($paginate) {
            $count = $this->markers->count();
            $offset = $page * $limit;
            // $items = array_slice($this->markers, (int) $offset, $limit, true);
            $items = $this->markers->slice((int) $offset, $limit);

            return new PaginationResponse($count, (int) ceil($count / $limit), !!count($items), $items);
        }

        return new SearchResult($this->markers->toArray());
    }


    public function update(int $id, array $values): ?Marker
    {
        $updatable = $this->findByID($id);
        if ($updatable) {
            $fields = [
                "text",
                "name",
                "title",
                "isActive",
            ];

            foreach ($fields as $key) {
                $updatable->$key = $values[$key];
            }
        }

        return $updatable;
    }

    public function delete(ModelInterface|int $subject): ?Marker
    {
        if (!is_int($subject)) {
            $jsonId = $subject->jsonSerialize()['id'];

            return $this->markers->remove($jsonId);
        }

        return $this->markers->remove($subject);
    }
}