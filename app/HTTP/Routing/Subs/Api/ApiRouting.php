<?php

namespace Core\HTTP\Routing\Subs\Api;

use App\Presentation\Actions\FileUpload\UploadAction;
use App\Presentation\Actions\Home\HomeController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group->get('/', HomeController::class);

    $group->group('/museum', require_once __DIR__.'/Museum/MuseumRouter.php');

    $group->group('/marker', require_once __DIR__.'/Marker/MarkerRouter.php');

    $group->post('/upload-file', UploadAction::class);
};
