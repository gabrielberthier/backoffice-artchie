<?php

namespace App\Infrastructure\Persistence\Museum;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Infrastructure\Persistence\PersistenceUtils\ItemsRetriever;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class MuseumDoctrineRepository implements MuseumRepository
{
    public function __construct(private EntityManager $em, private ItemsRetriever $itemsRetriever)
    {
    }

    public function update(int $id, array $values): ?Museum
    {
        $museum = $this->findByID($id);

        if ($museum) {
            try {
                $museum->setEmail($values['email'] ?? $museum->getEmail());
                $museum->setName($values['name'] ?? $museum->getName());

                $this->em->flush();
            } catch (UniqueConstraintViolationException) {
                throw new MuseumAlreadyRegisteredException();
            }
        }

        return $museum;
    }

    public function findByKey(string $key, mixed $value): ?Museum
    {
        return $this->em->getRepository(Museum::class)->findOneBy([$key => $value]);
    }

    public function findByMail(string $mail): ?Museum
    {
        return $this->em->getRepository(Museum::class)->findOneBy(['email' => $mail]);
    }

    public function findByUUID(string $uuid): ?Museum
    {
        return $this->em->getRepository(Museum::class)->findOneBy(['uuid' => $uuid]);
    }

    public function findByID(int $id): ?Museum
    {
        return $this->em->find(Museum::class, $id);
    }

    public function findByName(string $name): ?Museum
    {
        return $this->em->getRepository(Museum::class)->findOneBy(['name' => $name]);
    }

    public function insert(ModelInterface $museum): bool
    {
        try {
            $this->em->persist($museum);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException) {
            throw new MuseumAlreadyRegisteredException();
        }
    }

    public function add(Museum $museum): bool
    {
        try {
            $this->em->persist($museum);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException) {
            throw new MuseumAlreadyRegisteredException();
        }
    }

    public function delete(ModelInterface|int $museum): ?Museum
    {
        if (is_int($museum)) {
            $museum = $this->findByID($museum);
        }
        if ($museum) {
            $this->em->remove($museum);
            $this->em->flush();
        }

        return $museum;
    }

    public function findAll(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        return $this->itemsRetriever->findAll(Museum::class, $paginate, $page, $limit);
    }
}
