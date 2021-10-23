<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Markers\Utils\PresignedUrlMapper;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetAllMarkersByMuseumAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo,
        private PresignedUrlMapper $presignedUrlMapper
    ) {
    }

    public function action(): Response
    {
        $markers = [];
        $params = $this->request->getQueryParams();
        $museum_id = (int) $this->resolveArg('museum_id');

        if (!$museum_id) {
            throw new HttpBadRequestException($this->request);
        }

        if (isset($params['paginate'])) {
            $params['paginate'] = (bool) $params['paginate'];

            $markers = $this->repo->findAllByMuseum($museum_id, ...$params);
        } else {
            $markers = $this->repo->findAllByMuseum($museum_id);
        }

        $this->presignedUrlMapper->mapResponse($markers);

        return $this->respondWithData($markers);
    }
}
