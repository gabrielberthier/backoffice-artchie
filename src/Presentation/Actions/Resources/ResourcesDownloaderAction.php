<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Resources;

use App\Data\Protocols\Resources\ResourcesDownloaderInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResourcesDownloaderAction extends Action
{
    public function __construct(
        private ResourcesDownloaderInterface $downloader
    ) {
    }

    public function action(Request $request): Response
    {
        $id = $request->getAttribute('museum_id');

        $assets = $this->downloader->transport($id);

        return $this->respondWithData(array_values($assets));
    }
}
