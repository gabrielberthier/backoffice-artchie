<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

class OpenAppsDownloadMarkersAction extends Action
{
    public function __construct(
        private MarkerDownloaderServiceInterface $markerDonwloader
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->request->getAttribute('museum_id');

        return $this->response
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', 'attachment; filename=resources.zip')
            ->withAddedHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache')
            ->withBody((new Stream($this->markerDonwloader->downloadResourcesFromMuseum($id))));
    }
}
