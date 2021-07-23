<?php

namespace App\Infrastructure\Persistence\Museum;

use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class MuseumDoctrineRepository implements MuseumRepository
{
    public function __construct(private EntityManager $em)
    {
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

    public function insert(Museum $museum): bool
    {
        try {
            $this->em->persist($museum);
            $this->em->flush();

            return true;
        } catch (UniqueConstraintViolationException) {
            throw new MuseumAlreadyRegisteredException();
        }
    }

    public function delete(Museum $museum): Museum
    {
        $this->em->remove($museum);
        $this->em->flush();

        return $museum;
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Museum::class)->findAll();
    }
}
