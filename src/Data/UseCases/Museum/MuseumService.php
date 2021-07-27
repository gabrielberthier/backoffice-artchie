<?php

namespace App\Data\UseCases\User;

use App\Data\Protocols\User\UserUseCaseInterface;
use App\Domain\Repositories\MuseumRepository;

class MuseumService implements UserUseCaseInterface
{
    public function __construct(private MuseumRepository $repository)
    {
    }
}
