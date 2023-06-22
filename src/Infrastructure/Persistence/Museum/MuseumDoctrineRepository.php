<?php

namespace App\Infrastructure\Persistence\Museum;

use App\Data\Entities\Doctrine\DoctrineMuseum;
use App\Domain\Contracts\ModelInterface;
use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Infrastructure\ModelBridge\MuseumBridge;
use App\Infrastructure\Persistence\Abstraction\AbstractRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * @extends AbstractRepository<\App\Data\Entities\Doctrine\DoctrineMuseum>
 */
class MuseumDoctrineRepository extends AbstractRepository implements MuseumRepository
{
    public function entity(): string
    {
        return DoctrineMuseum::class;
    }

    public function update(int $id, array $values): ?Museum
    {
        /** @var DoctrineMuseum */
        $museum = $this->repository()->find($id);

        if ($museum) {
            try {
                $museum->setEmail($values['email'] ?? $museum->getEmail());
                $museum->setName($values['name'] ?? $museum->getName());

                $this->em->flush();
            } catch (UniqueConstraintViolationException) {
                throw new MuseumAlreadyRegisteredException();
            }
        }

        $museumBridge = new MuseumBridge();

        return $museumBridge->toModel($museum);
    }

    public function findByID(int $id): ?Museum
    {
        $museumBridge = new MuseumBridge();

        return $museumBridge->toModel(parent::findByID($id));
    }

    public function findByMail(string $mail): ?Museum
    {
        $museumBridge = new MuseumBridge();
        return $museumBridge->toModel($this->findByKey('email', $mail));
    }

    public function findByUUID(string $uuid): ?Museum
    {
        $museumBridge = new MuseumBridge();
        return $museumBridge->toModel($this->findByKey('uuid', $uuid));
    }

    public function findByName(string $name): ?Museum
    {
        $museumBridge = new MuseumBridge();
        return $museumBridge->toModel($this->findByKey('name', $name));
    }

    public function insert(ModelInterface $museum): bool
    {
        try {
            $museumBridge = new MuseumBridge();
            $museumDoctrine = $museumBridge->convertFromModel($museum);
            return parent::insert($museumDoctrine);
        } catch (UniqueConstraintViolationException) {
            throw new MuseumAlreadyRegisteredException();
        }
    }

    public function add(Museum $museum): bool
    {
        return $this->insert($museum);
    }

    public function remove(int $museum): ?Museum
    {
        $museumdb = parent::delete($museum);
        $museumBridge = new MuseumBridge();
        return is_null($museumdb) ? $museumdb : $museumBridge->toModel($museumdb);
    }

    public function all(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface
    {
        return parent::findAll(
            $paginate,
            $page,
            $limit = 20
        );
    }
}