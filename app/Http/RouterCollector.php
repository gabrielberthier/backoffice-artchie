<?php

declare(strict_types=1);

namespace Core\Http;

use App\Presentation\Actions\CycleTest\AccountGet;
use App\Presentation\Actions\CycleTest\AccountInsertion;
use Core\Http\Abstractions\AbstractRouterTemplate;
use Core\Http\Interfaces\RouteCollectorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Core\Http\Middlewares\AsymetricValidatorFactory;
use App\Presentation\Actions\Resources\ResourcesDownloaderAction;
use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;

class RouterCollector extends AbstractRouterTemplate
{
    public function collect(RouteCollectorInterface $routeCollector): void
    {
        $routeCollector->get('/', function ($request, Response $response) {
            $response->getBody()->write('Welcome to ARtchie\'s');

            return $response;
        });

        $this->setGroup('/users', 'users/users-routes');
        $this->setGroup('/auth', 'auth-routes');
        $this->setGroup('/api', 'api/api-routes');
        $this->setGroup('/social-login/google', 'social-login/google');

        $asymValidator = AsymetricValidatorFactory::createMiddleware($routeCollector->getContainer());
        $routeCollector
            ->get('/download-assets', OpenAppsDownloadMarkersAction::class)
            ->addMiddleware($asymValidator);
        $routeCollector
            ->get('/fetch-assets', ResourcesDownloaderAction::class)
            ->addMiddleware($asymValidator);

        $routeCollector->get('/acc', AccountGet::class);
        $routeCollector->post('/acc', AccountInsertion::class);
    }
}
