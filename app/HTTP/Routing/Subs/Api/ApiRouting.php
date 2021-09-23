<?php

namespace Core\HTTP\Routing\Subs\Api;

use App\Presentation\Actions\FileUpload\UploadAction;
use App\Presentation\Actions\Home\HomeController;
use App\Presentation\Actions\ResourcesSecurity\KeyCreatorAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group->get('/', HomeController::class);

    $group->group('/museum', require __DIR__.'/Museum/MuseumRouter.php');

    $group->group('/marker', require __DIR__.'/Marker/MarkerRouter.php');

    $group->post('/upload-file', UploadAction::class);

    $group->post('/create-app-key', KeyCreatorAction::class);

    $group->options('/', function (Request $request, Response $response): Response {
        // Do nothing here. Just return the response.
        return $response->withStatus(200);
    });
};
