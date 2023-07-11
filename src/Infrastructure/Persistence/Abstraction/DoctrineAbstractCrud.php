<?php
namespace App\Infrastructure\Persistence\Abstraction;

use App\Domain\Contracts\ModelInterface;
use App\Infrastructure\Persistence\TargetRepositories\DoctrineTargetRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Infrastructure\Persistence\Contracts\RepositoryInterface;

/**
 * @template T of object
 */
abstract class DoctrineAbstractCrud extends AbstractRepository
{
    public function __construct(protected EntityManager $em)
    {
    }

    public function repository(): RepositoryInterface
    {
        $repository = $this->em->getRepository($this->entity());

        return new DoctrineTargetRepository($repository);

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

    public function insert(ModelInterface $model): bool
    {
        $this->em->persist($model);
        $this->em->flush();

        return true;

    }
}