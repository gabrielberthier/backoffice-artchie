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

class InMemoryMarkerRepository implements MarkerRepositoryInterface
{
    private readonly array $markers;

    public function __construct()
    {
        $this->markers = [];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->markers);
    }

    /**
     * {@inheritdoc}
     */
    public function findByID(int $id): ?Marker
    {
        $all = $this->findAll();

        return $all[$id] ?? null;
    }

    public function add(Marker $model): bool
    {
        $this->markers[] = $model;

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
            $count = count($this->markers);
            $offset = $page * $limit;
            $items = array_slice($this->markers, $offset, $limit, true);

            return new PaginationResponse($count, (int) ceil($count / $limit), !!count($items), $items);
        }

        return new SearchResult($this->markers);
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
        $key = $subject;

        if (!is_int($subject)) {
            /** @var ?Marker */
            $el = array_search($subject, $this->markers);
            if ($el === null) {
                return null;
            }
            $key = $el->id;
        }

        if (!isset($this->markers[$key]) && !array_key_exists($key, $this->markers)) {
            return null;
        }

        $removed = $this->markers[$key];
        unset($this->markers[$key]);

        return $removed;
    }
}
