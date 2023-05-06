<?php

namespace Core\Http\Routing\Subs;

use App\Presentation\Actions\Auth\LoginController;
use App\Presentation\Actions\Auth\LogoutController;
use App\Presentation\Actions\Auth\RefreshTokenAction;
use App\Presentation\Actions\Auth\SignUpController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group->post('/login', LoginController::class);
    $group->post('/signup', SignUpController::class);
    $group->get('/logout', LogoutController::class);
    $group->get('/refresh-token', RefreshTokenAction::class);
};
