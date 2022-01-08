<?php

namespace App\Data\Protocols\BusinessEntities;

use App\Domain\Repositories\MuseumRepository;

interface MuseumServiceInterface
{
    public function __construct(MuseumRepository $repository);

    public function insert();
}
