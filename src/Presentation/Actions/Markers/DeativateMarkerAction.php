<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class DeativateMarkerAction extends Action
{
    public function __construct(
        private MarkerRepositoryInterface $repo
    ) {
    }

    public function action(Request $request): Response
    {
        $id = (int) $this->resolveArg('id');

        if ($id === 0) {
            throw new HttpBadRequestException($request, 'A valid ID should be passed');
        }

        $marker = $this->repo->update($id, ['isActive' => false]);

        if ($marker) {
            return $this->respondWithData($marker);
        }

        throw new HttpNotFoundException($request, 'A marker was not found using the provided id');
    }
}