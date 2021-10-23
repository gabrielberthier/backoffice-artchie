<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Markers\Utils\PresignedUrlMapper;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllMarkersAction extends Action
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
        if (isset($params['paginate'])) {
            $params['paginate'] = (bool) $params['paginate'];

            $markers = $this->repo->findAll(...$params);
        } else {
            $markers = $this->repo->findAll();
        }

        $this->presignedUrlMapper->mapResponse($markers);

        return $this->respondWithData($markers);
    }
}
