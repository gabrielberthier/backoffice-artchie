<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
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
            'email' => $email,
            'username' => $username,
            'password' => $password
        ] = $parsedBody;

        $credentials = new Credentials($email, $username, $password);
        $tokenize = $this->loginService->auth($credentials);
        $refreshToken = $tokenize->getRenewToken();

        setcookie(
            name: 'refresh-token',
            value: $refreshToken,
            expire: time() + 31536000,
            path: '/',
            httponly: true
        );

        return $this->respondWithData($tokenize)->withStatus(201, 'Created token');
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email not valid',
            'password' => 'Password wrong my dude',
        ];
    }

    public function rules(): ?array
    {
        return [
            'email' => Validator::email(),
            'username' => Validator::alnum()->noWhitespace()->length(6, 20),
            'password' => function ($value) { return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value); },
        ];
    }
}
