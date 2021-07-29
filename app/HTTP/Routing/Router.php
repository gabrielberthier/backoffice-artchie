<?php

declare(strict_types=1);

namespace Core\HTTP\Routing;

use App\Infrastructure\Downloader\S3\ResourceObject;
use App\Infrastructure\Downloader\S3\S3DownloaderFactory;
use App\Infrastructure\Downloader\S3\S3StreamObjectsZipDownloader;
use App\Presentation\Actions\Auth\LoginController;
use App\Presentation\Actions\Auth\LogoutController;
use App\Presentation\Actions\Auth\SignUpController;
use App\Presentation\Actions\Home\HomeController;
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
use Slim\Psr7\Stream;

class Router
{
    public static function run(App $app)
    {
        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write('Welcome to ARtchie\'s');

            return $response;
        });

        $app->get('/s3test', function (Request $request, Response $response) {
            $bucket = 'artchier-markers';
            $resource1 = new ResourceObject('04ecd2395ec93a13c5d154457cb12bf2.gif', '04ecd2395ec93a13c5d154457cb12bf2.gif');
            $resource2 = new ResourceObject('1 hDs9VPuus5w-9WmkU_rUqg.jpeg', '1 hDs9VPuus5w-9WmkU_rUqg.jpeg');
            $resource3 = new ResourceObject('42kntn (2).jpg', 'blabla.jpg');
            $resource4 = new ResourceObject('70085836_2373235036250630_6575964198579208192_n.png', 'blablabla2.jpg');

            $downloader = S3DownloaderFactory::create();
            $zipper = new S3StreamObjectsZipDownloader($downloader);
            $stream = $zipper->zipObjects(
                $bucket,
                [
                    $resource1,
                    $resource2,
                    $resource3,
                    $resource4,
                ]
            );

            return $response
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', 'attachment; filename=resources.zip')
                ->withAddedHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
                ->withHeader('Pragma', 'no-cache')
                ->withBody((new Stream($stream)))
            ;
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
        });
    }
}
