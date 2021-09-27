<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\DTO\Credentials;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class LoginController extends Action
{
    public function __construct(
        private LoginServiceInterface $loginService
    ) {
    }

    public function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();
        [
            'access' => $access,
            'password' => $password
        ] = $parsedBody;

        $credentials = new Credentials($access, $password);
        $tokenize = $this->loginService->auth($credentials);
        $refreshToken = $tokenize->getRenewToken();

        $domain = 'PRODUCTION' === $_ENV['MODE'] ? 'https://artchie-back-end.herokuapp.com' : '';

        setcookie(
            REFRESH_TOKEN,
            $refreshToken,
            [
                'expires' => time() + 31536000,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'None',
                'secure' => true,
            ]
        );

        return $this->respondWithData(['token' => $tokenize->getToken()])->withStatus(201, 'Created token');
    }

    public function messages(): ?array
    {
        return [
            'access' => 'Email or username is not valid',
            'password' => 'Password wrong my dude',
        ];
    }

    public function rules(): ?array
    {
        return [
            'access' => Validator::anyOf(
                Validator::email(),
                Validator::alnum()->noWhitespace()->length(6, 20)
            ),
            'password' => function ($value) { return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value); },
        ];
    }
}
