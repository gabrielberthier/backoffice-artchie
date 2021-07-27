<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Museum;

use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteMuseumAction extends Action
{
    public function __construct(
        private MuseumRepository $museumRepository
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $museum = $this->museumRepository->delete($id);

        return $this->respondWithData(['message' => 'Success! Museum deleted', 'museum' => $museum]);
    }
}
