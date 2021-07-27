<?php

namespace App\Data\UseCases\User;

use App\Domain\Repositories\MuseumRepository;

class MuseumService
{
    public function __construct(private MuseumRepository $repository)
    {
    }
}
