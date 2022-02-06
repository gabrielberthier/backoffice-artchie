<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;
use Core\HTTP\Routing\Interface\AbstractRouter;
use Core\HTTP\Routing\Middlewares\AsymetricValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class Router extends AbstractRouter
{
    public function define(App $app): void
    {
        $app->get('/', function ($request, Response $response) {
            $response->getBody()->write('Welcome to ARtchie\'s');

            return $response;
        });

        $this->setGroup('/users', 'Users/UserRouting');
        $this->setGroup('/auth', 'AuthRouter');
        $this->setGroup('/api', 'Api/ApiRouting');

        $asymValidator = AsymetricValidatorFactory::createMiddleware($app->getContainer());
        $app
            ->get('/download-assets', OpenAppsDownloadMarkersAction::class)
            ->addMiddleware($asymValidator);
    }
}
