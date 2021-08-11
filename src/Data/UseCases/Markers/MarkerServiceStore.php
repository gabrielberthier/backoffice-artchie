<?php

namespace App\Data\UseCases\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Domain\Models\Marker;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;

class MarkerServiceStore implements MarkerServiceStoreInterface
{
    public function __construct(
        private MuseumRepository $museumRepository,
        private MarkerRepositoryInterface $markerRepository,
    ) {
    }

    public function insert(int $museumId, Marker $marker): Marker
    {
        $museum = $this->museumRepository->findByID($museumId);
        $marker->setMuseum($museum);
        $this->markerRepository->insert($marker);

        return $marker;
    }
}
