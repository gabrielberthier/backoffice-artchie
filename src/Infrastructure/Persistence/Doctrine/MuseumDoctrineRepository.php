<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Data\Entities\Doctrine\DoctrineMuseum;
use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;
use App\Infrastructure\Persistence\Abstraction\AbstractRepository;
use App\Infrastructure\Persistence\Abstraction\DoctrineAbstractCrud;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * @extends AbstractRepository<DoctrineMuseum>
 */
class MuseumDoctrineRepository extends DoctrineAbstractCrud implements MuseumRepository
{
    public function __construct()
    {
    }

    public function entity(): string
    {
        return DoctrineMuseum::class;
    }

    public function update(int $id, array $values): ?Museum
    {
        /** @var ?DoctrineMuseum */
        $museum = $this->repository()->findByPK($id);

        if ($museum) {
            try {
                $museum->setEmail($values['email'] ?? $museum->getEmail());
                $museum->setName($values['name'] ?? $museum->getName());

                $this->em->flush();
            } catch (UniqueConstraintViolationException) {
                throw new MuseumAlreadyRegisteredException();
            }
        }

        return $museum?->toModel();
    }

    public function findByID(int $id): ?Museum
    {
        /** @var ?DoctrineMuseum */
        $museum = parent::findByID($id);

        return $museum?->toModel();
    }

    public function findByMail(string $mail): ?Museum
    {
        /** @var ?DoctrineMuseum */
        $doctrineMuseum = $this->findByKey('email', $mail);

        return $doctrineMuseum?->toModel();
    }

    public function findByUUID(string $uuid): ?Museum
    {
        /** @var ?DoctrineMuseum */
        $doctrineMuseum = $this->findByKey('uuid', $uuid);

        return $doctrineMuseum?->toModel();
    }

    public function findByName(string $name): ?Museum
    {
        /** @var ?DoctrineMuseum */
        $doctrineMuseum = $this->findByKey('name', $name);

        return $doctrineMuseum?->toModel();
    }

    /**
     * Inserts a museum model.
     *
     * @throws MuseumAlreadyRegisteredException
     */
    public function add(Museum $museum): bool
    {
        try {
            $museumDoctrine = new DoctrineMuseum();
            $this->insert($museumDoctrine->fromModel($museum));

            return true;
        } catch (UniqueConstraintViolationException) {
            throw new MuseumAlreadyRegisteredException();
        }
    }

    public function remove(int $museum): ?Museum
    {
        /** @var ?DoctrineMuseum */
        $museumdb = parent::delete($museum);

        return $museumdb?->toModel();
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
