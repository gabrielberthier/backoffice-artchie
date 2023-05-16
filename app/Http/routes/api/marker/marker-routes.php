<?php

namespace Core\Http\Routing\Subs\Api\Marker;

use App\Presentation\Actions\Markers\DeativateMarkerAction;
use App\Presentation\Actions\Markers\DeleteMarkerAction;
use App\Presentation\Actions\Markers\DownloadMarkerAction;
use App\Presentation\Actions\Markers\GetAllMarkersAction;
use App\Presentation\Actions\Markers\GetAllMarkersByMuseumAction;
use App\Presentation\Actions\Markers\GetOneMarkerByIdAction;
use App\Presentation\Actions\Markers\StoreMarkerAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $marker) {
    $marker->get('', GetAllMarkersAction::class);
    $marker->get('/{id}', GetOneMarkerByIdAction::class);
    $marker->get('/get-as-zip/{id}', DownloadMarkerAction::class);
    $marker->post('', StoreMarkerAction::class);
    $marker->get('/museum/{museum_id}', GetAllMarkersByMuseumAction::class);
    $marker->patch('/deativate/{id}', DeativateMarkerAction::class);
    $marker->delete('/{id}', DeleteMarkerAction::class);
};
