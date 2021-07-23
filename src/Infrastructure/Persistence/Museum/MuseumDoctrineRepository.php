<?php

namespace App\Infrastructure\Persistence\Account;

use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Account;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class MuseumDoctrineRepository implements MuseumRepository
{
    public function __construct(private EntityManager $em)
    {
    }

    public function findByMail(string $mail): ?Account
    {
        return $this->em->getRepository(Account::class)->findOneBy(['email' => $mail]);
    }

    public function findByUUID(string $uuid): ?Museum
    {
        return $this->em->getRepository(Account::class)->findOneBy(['uuid' => $uuid]);
    }

    public function findByID(int $id): ?Museum
    {
        return $this->em->find(Museum::class, $id);
    }

    public function findByName(string $name): ?Museum
    {
        return $this->em->getRepository(Account::class)->findOneBy(['name' => $name]);
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
}
