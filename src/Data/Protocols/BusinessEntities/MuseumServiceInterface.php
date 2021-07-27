<?php

namespace App\Data\Protocols\User;

use App\Domain\Repositories\MuseumRepository;

interface MuseumServiceInterface
{
    public function __construct(MuseumRepository $repository);

    public function insert();
}
