<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MemoryRepositories;

use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\SearchResult;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class InMemoryMuseumRepository implements MuseumRepository
{
    private readonly Collection $museums;

    public function __construct()
    {
        $this->museums = new ArrayCollection(
            [
                new Museum(1, 'bill.gates@mail.com', 'Bill', 'Gates', 'Info'),
                new Museum(
                    id: 2,
                    email: 'steve.jobs@mail.com',
                    name: 'steve.jobs',
                    description: 'Description about ' . 'steve.jobs',
                    info: 'Info about ' . 'steve.jobs'
                ),
                new Museum(
                    id: 3,
                    email: 'mark.zuckerberg@mail.com',
                    name: 'mark.zuckerberg',
                    description: 'Description about ' . 'mark.zuckerberg',
                    info: 'Info about ' . 'mark.zuckerberg'
                ),
                new Museum(
                    id: 4,
                    email: 'evan.spiegel@mail.com',
                    name: 'evan.spiegel',
                    description: 'Description about ' . 'evan.spiegel',
                    info: 'Info about ' . 'evan.spiegel'
                ),
                new Museum(
                    id: 5,
                    email: 'jack.dorsey@mail.com',
                    name: 'jack.dorsey',
                    description: 'Description about ' . 'jack.dorsey',
                    info: 'Info about ' . 'jack.dorsey'
                ),
            ]
        );
    }

    public function findByID(int $id): ?Museum
    {
        return $this->museums->get($id);
    }

    public function findByName(string $name): ?Museum
    {
        return $this->museums->findFirst(fn(Museum $el) => $el->name === $name);
    }

    public function findByUUID(string $uuid): ?Museum
    {
        return $this->museums->findFirst(fn(Museum $el) => $el->uuid->equals(Uuid::fromString($uuid)));
    }

    /**
     * Inserts a museum model.
     *
     * @throws \App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException
     */
    public function add(Museum $model): bool
    {
        $this->museums->add($model);

        return true;
    }

    public function remove(int $museum): ?Museum
    {
        return $this->museums->remove($museum);
    }

    public function all(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        if ($paginate) {
            $count = $this->museums->count();

            $offset = $page * $limit;

            $items = $this->museums->slice((int) $offset, $limit);

            return new PaginationResponse($count, (int) ceil($count / $limit), !!count($items), $items);
        }

        return new SearchResult($this->museums->toArray());
    }
}