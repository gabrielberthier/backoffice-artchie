<?php

declare(strict_types=1);

namespace Core\Http;


use Slim\App;
use League\OAuth2\Client\Provider\Google;
use Psr\Http\Message\ServerRequestInterface;
use Core\Http\Interfaces\AbstractRouterTemplate;
use Psr\Http\Message\ResponseInterface as Response;
use Core\Http\Middlewares\AsymetricValidatorFactory;
use App\Presentation\Actions\Resources\ResourcesDownloaderAction;
use App\Presentation\Actions\Markers\OpenAppsDownloadMarkersAction;

class RouterCollector extends AbstractRouterTemplate
{
    public function define(App $app): void
    {
        $app->get('/', function ($request, Response $response) {
            $response->getBody()->write('Welcome to ARtchie\'s');

            return $response;
        });

        $this->setGroup('/users', 'users/users-routes');
        $this->setGroup('/auth', 'auth-routes');
        $this->setGroup('/api', 'api/api-routes');

        $asymValidator = AsymetricValidatorFactory::createMiddleware($app->getContainer());
        $app
            ->get('/download-assets', OpenAppsDownloadMarkersAction::class)
            ->addMiddleware($asymValidator);
        $app
            ->get('/fetch-assets', ResourcesDownloaderAction::class)
            ->addMiddleware($asymValidator);
        $app->get('/generate-google-login', function (ServerRequestInterface $request, Response $response, array $args) {
            $provider = new Google([
                'clientId' => '363596219955-do13si4usa5drq47ih5f0fh12ppl5og5.apps.googleusercontent.com',
                'clientSecret' => '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',
                'redirectUri' => 'http://localhost:8000/google-login',
            ]);

            $response->getBody()->write($provider->getAuthorizationUrl());

            return $response;
        });

        $app->get('/google-login', function (ServerRequestInterface $request, Response $response, array $args) {
            $params = $request->getQueryParams();
            $provider = new Google([
                'clientId' => '363596219955-do13si4usa5drq47ih5f0fh12ppl5og5.apps.googleusercontent.com',
                'clientSecret' => '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',
                'redirectUri' => 'http://localhost:8000/google-login',
            ]);

            $token = $provider->getAccessToken('authorization_code', [
                'code' => $params['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the owner details
                $ownerDetails = $provider->getResourceOwner($token);

                $data = [
                    "sub" => "100122219187328661430",
                    "name" => "Gabriel Nogueira",
                    "given_name" => "Gabriel",
                    "family_name" => "Nogueira",
                    "picture" => "https://lh3.googleusercontent.com/a/AGNmyxYquL0yPgBJvAcbwNDUBwhhFmGaN3Re1QvWLraIF5U=s96-c",
                    "email" => "gnberthier@gmail.com",
                    "email_verified" => true,
                    "locale" => "pt-BR"
                ];

                // Use these details to create a new profile
                dd($ownerDetails->toArray());

                return $response;

            } catch (\Exception $e) {

                // Failed to get user details
                exit('Something went wrong: ' . $e->getMessage());

            }
        });
    }
}