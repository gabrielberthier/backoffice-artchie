<?php

namespace Core\Http\Routing\Subs;

use App\Presentation\Actions\SocialLogin\GetAuthUrlGoogleAction;
use App\Presentation\Actions\SocialLogin\GoogleAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (Group $group) {
    $group->get('/generate-login', GetAuthUrlGoogleAction::class);

    $group->get('/google-login', GoogleAction::class);
};