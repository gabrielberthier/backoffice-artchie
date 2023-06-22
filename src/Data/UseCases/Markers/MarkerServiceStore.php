<?php

namespace App\Data\UseCases\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use App\Domain\Exceptions\Protocols\UniqueConstraintViolation\AbstractUniqueException;
use App\Domain\Exceptions\Transaction\InstanceNotFoundException;
use App\Domain\Exceptions\Transaction\NameAlreadyInUse;
use App\Domain\Models\Marker\Marker;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Exception;

class MarkerServiceStore implements MarkerServiceStoreInterface
{
    public function __construct(
        private MuseumRepository $museumRepository,
        private MarkerRepositoryInterface $markerRepository,
        private EntityManager $em
    ) {
    }

    public function insert(int $museumId, Marker $marker): Marker
    {
        $this->em->getConnection()->beginTransaction();

        try {
            $museum = $this->museumRepository->findByID($museumId);

            if (!$museum) {
                throw new InstanceNotFoundException('Museum');
            }
            $input = [...$marker->jsonSerialize()];
            $input['museum'] = $museum;
            $marker = new Marker(
                ...$input
            );

            $this->markerRepository->add($marker);
            $this->em->getConnection()->commit();

            return $marker;
        } catch (AbstractUniqueException $exception) {
            throw new NameAlreadyInUse($exception->getResponseMessage(), 400, $exception);
        } catch (HttpSpecializedAdapter $exception) {
            throw $exception;
        } catch (Exception $ex) {
            $this->em->getConnection()->rollBack();

            echo $ex;

            throw $ex;
        }
    }
}