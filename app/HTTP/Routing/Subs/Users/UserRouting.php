<?php

namespace Core\HTTP\Routing\Subs;

use App\Presentation\Actions\User\ListUsersAction;
use App\Presentation\Actions\User\ViewUserAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group->get('', ListUsersAction::class);
    $group->get('/{id}', ViewUserAction::class);
};
