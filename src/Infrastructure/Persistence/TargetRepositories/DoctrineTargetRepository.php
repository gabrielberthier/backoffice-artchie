<?php
namespace App\Infrastructure\Persistence\TargetRepositories;

use App\Infrastructure\Persistence\Contracts\RepositoryInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineTargetRepository implements RepositoryInterface
{
    public function __construct(private ObjectRepository $repository)
    {
    }

    public function findByPK(mixed $id): ?object
    {
        return $this->repository->find($id);
    }

    public function findOne(array $criteria = []): ?object
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): iterable {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findAll(array $scope = []): iterable
    {
        return $this->repository->findAll();
    }

    public function entityTargetName(): string
    {
        return $this->repository->getClassName();
    }
}