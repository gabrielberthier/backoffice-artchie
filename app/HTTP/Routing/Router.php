<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Presentation\Actions\Auth\LoginController;
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
            $response->getBody()->write('Hello world!');

            return $response;
        });

        $app->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id}', ViewUserAction::class);
        });

        $app->group('/auth', function (Group $group) {
            $group->post('/login', LoginController::class);
        });
    }
}
