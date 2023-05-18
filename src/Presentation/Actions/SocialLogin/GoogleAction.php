<?php
declare(strict_types=1);


namespace App\Presentation\Actions\SocialLogin;


use App\Data\UseCases\Authentication\SocialLoginAuth;
use App\Data\UseCases\SocialLogin\GoogleAuthProvider;
use App\Presentation\Actions\Auth\Utilities\CookieTokenManager;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class GoogleAction extends Action
{
    public function __construct(
        private GoogleAuthProvider $provider,
        private CookieTokenManager $cookieTokenManager,
        private SocialLoginAuth $socialLoginAuth
    ) {

    }


    /**
     * {@inheritdoc}
     */
    public function action(Request $request): Response
    {
        $params = $request->getQueryParams();

        $code = $params['code'];
        $dto = $this->provider->getAuthUser($code);
        $tokenize = $this->socialLoginAuth->authenticate($dto);
        $refreshToken = $tokenize->renewToken;

        $this->cookieTokenManager->implant($refreshToken);

        return $this->respondWithData(['token' => $tokenize->token])->withStatus(201, 'Created token');

    }
}