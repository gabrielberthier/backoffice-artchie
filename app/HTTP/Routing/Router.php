<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;
use Core\HTTP\Routing\RouteMiddlewares\AsymetricValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class Router
{
    public function run(App $app)
    {
        $auth = $this->retrieveRouting('AuthRouter');
        $usersTest = $this->retrieveRouting('Users/UserRouting');
        $api = $this->retrieveRouting('Api/ApiRouting');

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

        $app->options(
            '/{routes:.+}',
            fn ($request, $response, $args) => $response
        );
    }

    private function retrieveRouting(string $path)
    {
        return require __DIR__."/Subs/{$path}.php";
    }
}
