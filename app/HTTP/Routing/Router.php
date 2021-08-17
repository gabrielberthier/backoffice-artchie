<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Presentation\Actions\Auth\LoginController;
use App\Presentation\Actions\Auth\LogoutController;
use App\Presentation\Actions\Auth\SignUpController;
use App\Presentation\Actions\FileUpload\UploadAction;
use App\Presentation\Actions\Home\HomeController;
use App\Presentation\Actions\Markers\DownloadMarkerAction;
use App\Presentation\Actions\Markers\GetAllMarkersAction;
use App\Presentation\Actions\Markers\GetAllMarkersByMuseumAction;
use App\Presentation\Actions\Markers\StoreMarkerAction;
use App\Presentation\Actions\Museum\CreateMuseumAction;
use App\Presentation\Actions\Museum\DeleteMuseumAction;
use App\Presentation\Actions\Museum\GetAllMuseumAction;
use App\Presentation\Actions\Museum\SelectOneMuseumAction;
use App\Presentation\Actions\Museum\UpdateMuseumAction;
use App\Presentation\Actions\User\ListUsersAction;
use App\Presentation\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

class Router
{
    public static function run(App $app)
    {
        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write('Welcome to ARtchie\'s');

            return $response;
        });

        $app->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id}', ViewUserAction::class);
        });

        $app->group('/auth', function (Group $group) {
            $group->post('/login', LoginController::class);
            $group->post('/signup', SignUpController::class);
            $group->get('/logout', LogoutController::class);
        });

        $app->group('/api', function (Group $group) {
            $group->get('/', HomeController::class);

            $group->group('/museum', function (Group $museum) {
                $museum->get('/', GetAllMuseumAction::class);
                $museum->post('/', CreateMuseumAction::class);
                $museum->put('/{id}', UpdateMuseumAction::class);
                $museum->delete('/{id}', DeleteMuseumAction::class);
                $museum->get('/{id}', SelectOneMuseumAction::class);
            });

            $group->group('/marker', function (Group $marker) {
                $marker->get('/get-as-zip', DownloadMarkerAction::class);
                $marker->post('/', StoreMarkerAction::class);
                $marker->get('/', GetAllMarkersAction::class);
                $marker->get('/museum/{museum_id}', GetAllMarkersByMuseumAction::class);
            });

            $group->post('/upload-file', UploadAction::class);
        });
    }
}
