<?php
namespace App\Infrastructure\Persistence\Contracts;

/**
 * Defines ability to locate entities based on scope parameters.
 *
 * @template TEntity of object
 */
interface RepositoryInterface
{
    /** @return TEntity|null */
    public function findByPK(mixed $id): ?object;

    /** @return TEntity|null */
    public function findOne(array $criteria = []): ?object;

    /** @return iterable<TEntity> */
    public function findAll(): iterable;

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     * @psalm-param array<string, 'asc'|'desc'|'ASC'|'DESC'>|null $orderBy
     *
     * @return array<int, object> The objects.
     * @psalm-return TEntity[]
     */
    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): iterable;

    public function entityTargetName(): string;
}