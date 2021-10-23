<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use function _\map;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use S3DataTransfer\S3\Factories\ClientProvider;

class GetAllMarkersAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo
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

        $s3client = ClientProvider::getS3Client(
            $_ENV['S3KEY'],
            $_ENV['S3SECRET'],
            $_ENV['S3REGION'],
            $_ENV['S3VERSION'],
        );

        map($markers, function (): array {
            return [];
        });

        return $this->respondWithData($markers);
    }
}
