<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Markers\Utils\PresignedUrlCreator;
use App\Presentation\Actions\Protocols\Action;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetOneMarkerByIdAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo,
        private PresignedUrlCreator $presignedUrlCreator
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->resolveArg('id');

        if (!$id) {
            throw new HttpBadRequestException($this->request, 'A valid ID should be passed');
        }

        $marker = $this->repo->findByID($id);

        if (!$marker) {
            throw new NotFoundException('No marker found using this id');
        }
        if ($asset = $marker->getAsset()) {
            $asset->setTemporaryLocation($this->presignedUrlCreator->setPresignedUrl($asset));
        }

        foreach ($marker->getResources() as $res) {
            if ($asset = $res->getAsset()) {
                $asset->setTemporaryLocation($this->presignedUrlCreator->setPresignedUrl($asset));
            }
        }

        return $this->respondWithData($marker);
    }
}
