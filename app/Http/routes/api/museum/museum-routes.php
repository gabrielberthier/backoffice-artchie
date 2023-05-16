<?php

namespace Core\Http\Routing\Subs\Api\Museum;

use App\Presentation\Actions\Museum\CreateMuseumAction;
use App\Presentation\Actions\Museum\DeleteMuseumAction;
use App\Presentation\Actions\Museum\GetAllMuseumAction;
use App\Presentation\Actions\Museum\SelectOneMuseumAction;
use App\Presentation\Actions\Museum\UpdateMuseumAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $museum) {
    $museum->get('', GetAllMuseumAction::class);
    $museum->post('', CreateMuseumAction::class);
    $museum->put('/{id}', UpdateMuseumAction::class);
    $museum->delete('/{id}', DeleteMuseumAction::class);
    $museum->get('/{id}', SelectOneMuseumAction::class);
};
