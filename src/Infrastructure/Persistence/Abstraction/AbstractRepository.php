<?php

namespace App\Infrastructure\Persistence\Abstraction;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Repositories\PersistenceOperations\CrudOperationsInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Infrastructure\Persistence\PersistenceUtils\ItemsRetriever;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 */
abstract class AbstractRepository implements CrudOperationsInterface
{

  public function __construct(protected EntityManager $em, protected ItemsRetriever $itemsRetriever)
  {
  }

  /**
   * @return class-string<T>
   */
  public abstract function entity(): string;

  /**
   * @return ObjectRepository<T>
   */
  protected function repository(): ObjectRepository
  {
    return $this->em->getRepository($this->entity());
  }

  /**
   * @return ResultSetInterface
   */
  public function findAll(bool $paginate = false, int $page = 1, int $limit = 20): ResultSetInterface
  {
    return $this->itemsRetriever->findAll($this->entity(), $paginate, $page, $limit);
  }

  /**
   * @return ?T
   */
  public function findByKey(string $key, mixed $value): ?object
  {
    return $this->repository()->findOneBy([$key => $value]);
  }

  /**
   * @return T[]
   */
  public function findItemsByKey(string $key, mixed $value): array
  {
    return $this->repository()->findBy([$key => $value]);
  }

  /**
   * @return ?T
   */
  public function findByID(int $id): ?object
  {
    return $this->em->find($this->entity(), $id);
  }

  /**
   * @param array $conditions
   * 
   * @return T[]
   */
  public function findWithConditions(array $conditions): array
  {
    return $this->repository()->findBy($conditions);
  }

  /**
   * @param T|int $subject
   * 
   * @return ?T
   */
  public function delete(ModelInterface|int $subject): ?object
  {
    if (is_int($subject)) {
      $subject = $this->findByID($subject);
    }
    if ($subject) {
      $this->em->remove($subject);
      $this->em->flush();
    }

    return $subject;
  }

  /**
   * @param T
   * 
   * @return bool
   */
  public function insert(ModelInterface $model): bool
  {
    $this->em->persist($model);
    $this->em->flush();

    return true;
  }
}