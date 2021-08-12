<?php

namespace Tests\Domain\UseCases\Markers\Store;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Data\UseCases\Markers\MarkerServiceStore;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;

class SutTypes
{
    public MarkerServiceStoreInterface $service;

    public function __construct(
        public MuseumRepository $museumRepository,
        public MarkerRepositoryInterface $markerRepositoryInterface
    ) {
        $this->service = new MarkerServiceStore($museumRepository, $markerRepositoryInterface);
    }
}
