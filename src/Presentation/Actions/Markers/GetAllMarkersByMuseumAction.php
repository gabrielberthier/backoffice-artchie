<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class GetAllMarkersByMuseumAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo,
    ) {
    }

    public function action(Request $request): Response
    {
        $markers = [];
        $params = $request->getQueryParams();
        $museum_id = (int) $this->resolveArg('museum_id');

        if ($museum_id === 0) {
            throw new HttpBadRequestException($request);
        }

        if (isset($params['paginate'])) {
            $params['paginate'] = (bool) $params['paginate'];

            $markers = $this->repo->findAllByMuseum($museum_id, ...$params);
        } else {
            $markers = $this->repo->findAllByMuseum($museum_id);
        }

        return $this->respondWithData($markers);
    }
}
