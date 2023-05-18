<?php
declare(strict_types=1);


namespace App\Presentation\Actions\SocialLogin;


use App\Presentation\Actions\Protocols\Action;
use League\OAuth2\Client\Provider\Google;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class GetAuthUrlGoogleAction extends Action
{
    public function __construct(
        private Google $provider
    ) {

    }


    /**
     * {@inheritdoc}
     */
    public function action(Request $request): Response
    {
        return $this->respondWithData(['auth_url' => $this->provider->getAuthorizationUrl()]);
    }
}