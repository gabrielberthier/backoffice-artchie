<?php

namespace App\Infrastructure\Persistence\Museum;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Infrastructure\Persistence\Abstract\AbstractRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class MuseumDoctrineRepository extends AbstractRepository implements MuseumRepository
{
    public function entity(): string
    {
        return Museum::class;
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

    public function findByID(int $id): ?Museum
    {
        return parent::findByID($id);
    }

    public function findByMail(string $mail): ?Museum
    {
        return $this->findByKey('email', $mail);
    }

    public function findByUUID(string $uuid): ?Museum
    {
        return $this->findByKey('uuid', $uuid);
    }

    public function findByName(string $name): ?Museum
    {
        return $this->findByKey('name', $name);
    }

    public function insert(ModelInterface $museum): bool
    {
        try {
            return $this->insert($museum);
        } catch (UniqueConstraintViolationException) {
            throw new MuseumAlreadyRegisteredException();
        }
    }

    public function add(Museum $museum): bool
    {
        return $this->insert($museum);
    }

    public function delete(ModelInterface|int $subject): ?Museum
    {
        return parent::delete($subject);
    }
}
