<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Data\Protocols\AsymCrypto\AsymmetricVerifier;
use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Markers\DownloadMarkerAction;
use App\Presentation\Middleware\AsymmetricValidator;
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

        $container = $app->getContainer();
        $repository = $container->get(MuseumRepository::class);
        $verifier = $container->get(AsymmetricVerifier::class);
        $middleware = new AsymmetricValidator($repository, $verifier);

        $app->get('/download-assets', DownloadMarkerAction::class)->addMiddleware($middleware);
    }

    private function retrieveRouting(string $path)
    {
        return require __DIR__."/Subs/{$path}.php";
    }
}
