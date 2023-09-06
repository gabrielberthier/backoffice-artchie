<?php
namespace App\Infrastructure\Persistence\Abstraction;

use App\Domain\Contracts\ModelInterface;
use App\Infrastructure\Persistence\TargetRepositories\CycleOrmTargetRepository;
use App\Infrastructure\Persistence\Contracts\RepositoryInterface;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORM;

/**
 * @template T of object
 */
abstract class CycleAbstractCrud extends AbstractRepository
{
    protected EntityManager $entityManager;

    public function __construct(protected ORM $orm)
    {
        $this->entityManager = new EntityManager($orm);
    }

    public function repository(): RepositoryInterface
    {
        $repository = $this->orm->getRepository($this->entity());

        return new CycleOrmTargetRepository($repository);

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

        if ($subject !== null) {
            $this->entityManager->delete($subject);
            $this->entityManager->run();
        }

        return $subject;
    }

    public function insert(ModelInterface $model): bool
    {
        $this->entityManager->persist($model);
        $this->entityManager->run();

        return true;

    }
}