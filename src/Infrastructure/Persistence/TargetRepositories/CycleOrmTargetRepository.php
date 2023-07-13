<?php
namespace App\Infrastructure\Persistence\TargetRepositories;

use App\Infrastructure\Persistence\Contracts\RepositoryInterface;
use Cycle\ORM\Select\Repository as CycleRepository;


class CycleOrmTargetRepository implements RepositoryInterface
{
    public function __construct(private CycleRepository $repository)
    {

    }

    public function findByPK(mixed $id): ?object
    {
        return $this->repository->findByPK($id);
    }

    public function findOne(array $criteria = []): ?object
    {
        return $this->repository->findOne($criteria);
    }

    public function findAll(array $scope = []): iterable
    {
        return $this->repository->findAll();
    }

    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): iterable {
        $select = $this->repository->select()->where($criteria);
        if ($orderBy) {
            $select = $select->orderBy(...$orderBy);
        }

        if ($limit) {
            $select = $select->limit($limit);
        }

        if ($offset) {
            $select = $select->offset($offset);
        }

        return $select->fetchAll();

    }

    public function entityTargetName(): string
    {
        return "";
    }
}