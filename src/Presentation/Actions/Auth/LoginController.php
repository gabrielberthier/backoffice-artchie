<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Dto\Credentials;
use App\Presentation\Actions\Auth\Utilities\CookieTokenManager;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class LoginController extends Action
{
    private CookieTokenManager $cookieManager;

    public function __construct(
        private LoginServiceInterface $loginService
    ) {
        $this->cookieManager = new CookieTokenManager();
    }

    public function action(Request $request): Response
    {
        $parsedBody = $request->getParsedBody();
        [
            'access' => $access,
            'password' => $password
        ] = $parsedBody;

        $credentials = new Credentials($access, $password);
        $tokenize = $this->loginService->auth($credentials);
        $refreshToken = $tokenize->renewToken;

        $this->cookieManager->implant($refreshToken);

        return $this->respondWithData(['token' => $tokenize->token])->withStatus(201, 'Created token');
    }

    public function messages(): ?array
    {
        return [
            'access' => 'Email or username is not valid',
            'password' => 'Password wrong my dude',
        ];
    }

    public function rules(Request $request): ?array
    {
        return [
            'access' => Validator::anyOf(
                Validator::email(),
                Validator::alnum()->noWhitespace()->length(6, 20)
            ),
            'password' => static function ($value) {
                return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value);
            },
        ];
    }
}