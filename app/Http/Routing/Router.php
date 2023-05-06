<?php

declare(strict_types=1);

namespace Core\Http\Routing;

use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;
use App\Presentation\Actions\Resources\ResourcesDownloaderAction;
use Core\Http\Routing\Interface\AbstractRouter;
use Core\Http\Routing\Middlewares\AsymetricValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
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
        $app
            ->get('/fetch-assets', ResourcesDownloaderAction::class)
            ->addMiddleware($asymValidator);
    }
}
