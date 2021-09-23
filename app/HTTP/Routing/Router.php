<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;
use Core\HTTP\Routing\RouteMiddlewares\AsymetricValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpNotFoundException;

class Router
{
    public function run(App $app)
    {
        $auth = $this->retrieveRouting('AuthRouter');
        $usersTest = $this->retrieveRouting('Users/UserRouting');
        $api = $this->retrieveRouting('Api/ApiRouting');

        $app->options(
            '/{routes:.+}',
            fn (Request $request, Response $response, $args) => $response->withStatus(200, 'Preflight is on')
        );

        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write('Welcome to ARtchie\'s');

            return $response;
        });

        $app->group('/users', $usersTest);

        $app->group('/auth', $auth);

        $app->group('/api', $api);

        $app->get('/download-assets', OpenAppsDownloadMarkersAction::class)
            ->addMiddleware(
                AsymetricValidatorFactory::createMiddleware($app->getContainer())
            )
        ;

        $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
            throw new HttpNotFoundException($request);
        });
    }

    private function retrieveRouting(string $path)
    {
        return require __DIR__."/Subs/{$path}.php";
    }
}
