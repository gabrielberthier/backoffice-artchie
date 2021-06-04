<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Domain\Models\Account;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class SignUpController extends Action
{
    public function __construct(
        private SignUpServiceInterface $service
    ) {
    }

    public function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();
        [
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ] = $parsedBody;

        $tokenize = $this->service->register(new Account(email: $email, username: $username, password: $password));
        $refreshToken = $tokenize->getRenewToken();

        setcookie(
            name: REFRESH_TOKEN,
            value: $refreshToken,
            expires_or_options: time() + 31536000,
            path: '/',
            httponly: true
        );

        return $this->respondWithData($tokenize->getToken())->withStatus(201, 'Created token');
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email not valid',
            'username' => 'A valid username must be provided',
            'password' => 'Password wrong my dude',
            'password_confirmation' => 'Password confirmation doesn\'t match.',
        ];
    }

    public function rules(): ?array
    {
        $parsedBody = $this->request->getParsedBody();
        $password = $parsedBody['password'];

        return [
            'email' => Validator::email(),
            'username' => Validator::alnum()->noWhitespace()->length(6, 20),
            'password' => function ($value) { return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value); },
            'passwordConfirmation' => fn ($value) => $value === $password,
        ];
    }
}
