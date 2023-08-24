<?php

namespace Core\Http\Routing\Subs;

use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;
use App\Presentation\Actions\Resources\ResourcesDownloaderAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group
        ->get('/download-assets', OpenAppsDownloadMarkersAction::class);
    $group
        ->get('/fetch-assets', ResourcesDownloaderAction::class);
};