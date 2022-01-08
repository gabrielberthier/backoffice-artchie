<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Museum;

use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllMuseumAction extends Action
{
    public function __construct(
        private MuseumRepository $museumRepository
    ) {
    }

    public function action(): Response
    {
        $museums = [];
        $params = $this->request->getQueryParams();
        if (isset($params['paginate'])) {
            $params['paginate'] = (bool) $params['paginate'];

            $museums = $this->museumRepository->findAll(...$params);
        } else {
            $museums = $this->museumRepository->findAll();
        }

        return $this->respondWithData($museums);
    }
}
