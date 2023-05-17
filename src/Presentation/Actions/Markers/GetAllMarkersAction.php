<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetAllMarkersAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo,
    ) {
    }

    public function action(Request $request): Response
    {
        $markers = [];
        $params = $request->getQueryParams();
        if (isset($params['paginate'])) {
            $params['paginate'] = (bool) $params['paginate'];

            $markers = $this->repo->findAll(...$params);
        } else {
            $markers = $this->repo->findAll();
        }

        return $this->respondWithData($markers);
    }
}
