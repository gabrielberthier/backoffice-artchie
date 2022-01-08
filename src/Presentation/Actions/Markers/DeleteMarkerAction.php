<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class DeleteMarkerAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->resolveArg('id');

        if (!$id) {
            throw new HttpBadRequestException($this->request, 'A valid ID should be passed');
        }

        $marker = $this->repo->delete($id);

        if ($marker) {
            return $this->respondWithData($marker);
        }

        throw new HttpNotFoundException($this->request, 'A marker was not found using designated id');
    }
}
