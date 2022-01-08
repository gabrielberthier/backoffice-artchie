<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Museum;

use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class SelectOneMuseumAction extends Action
{
    public function __construct(
        private MuseumRepository $museumRepository
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $museum = $this->museumRepository->findByID($id);

        return $this->respondWithData(['museum' => $museum]);
    }
}
