<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Stream;

class DownloadMarkerAction extends Action
{
    public function __construct(
        private MarkerDownloaderServiceInterface $markerDonwloader
    ) {
    }

    public function action(Request $request): Response
    {
        $id = (int) $this->resolveArg('id');

        return $this->response
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', 'attachment; filename=resources.zip')
            ->withAddedHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache')
            ->withBody((new Stream($this->markerDonwloader->downloadResourcesFromMuseum($id))));
    }
}
