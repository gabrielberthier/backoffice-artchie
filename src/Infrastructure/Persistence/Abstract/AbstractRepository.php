<?php

namespace App\Infrastructure\Persistence\Abstract;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Repositories\PersistenceOperations\CrudOperationsInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Infrastructure\Persistence\PersistenceUtils\ItemsRetriever;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractRepository implements CrudOperationsInterface
{

  public function __construct(protected EntityManager $em, protected ItemsRetriever $itemsRetriever)
  {
  }

  public abstract function entity(): string;

  protected function repository(): ObjectRepository
  {
    return $this->em->getRepository($this->entity());
  }

  public function findAll(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
  {
    return $this->itemsRetriever->findAll($this->entity(), $paginate, $page, $limit);
  }

  public function findByKey(string $key, mixed $value): ?ModelInterface
  {
    return $this->repository()->findOneBy([$key => $value]);
  }

  public function findItemsByKey(string $key, mixed $value): array
  {
    return $this->repository()->findBy([$key => $value]);
  }

  public function findByID(int $id): ?ModelInterface
  {
    return $this->em->find($this->entity(), $id);
  }

  public function findWithConditions(array $conditions): array
  {
    return $this->repository()->findBy($conditions);
  }

  public function delete(ModelInterface|int $subject): ?ModelInterface
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

  public function insert(ModelInterface $model): bool
  {
    $this->em->persist($model);
    $this->em->flush();

    return true;
  }
}
