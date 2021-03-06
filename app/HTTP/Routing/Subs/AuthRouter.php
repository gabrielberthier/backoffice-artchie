<?php

namespace Core\HTTP\Routing\Subs;

use App\Presentation\Actions\Auth\LoginController;
use App\Presentation\Actions\Auth\LogoutController;
use App\Presentation\Actions\Auth\SignUpController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group->post('/login', LoginController::class);
    $group->post('/signup', SignUpController::class);
    $group->get('/logout', LogoutController::class);
};
