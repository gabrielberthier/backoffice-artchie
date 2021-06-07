<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Data\Protocols\Cryptography\HasherInterface;
use App\Domain\Models\Account;
use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Actions\Protocols\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;
use Throwable;

class SignUpController extends Action
{
    public function __construct(
        private SignUpServiceInterface $service,
        private HasherInterface $hasherInterface
    ) {
    }

    public function action(): Response
    {
        try {
            $parsedBody = $this->request->getParsedBody();
            [
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ] = $parsedBody;

            $password = $this->hasherInterface->hash($password);
            $account = new Account(email: $email, username: $username, password: $password);
            $tokenize = $this->service->register($account);
            $refreshToken = $tokenize->getRenewToken();

            setcookie(
                name: REFRESH_TOKEN,
                value: $refreshToken,
                expires_or_options: time() + 31536000,
                path: '/',
                httponly: true
            );

            return $this
                ->respondWithData(['token' => $tokenize->getToken()])
                ->withStatus(201, 'Created token')
        ;
        } catch (Throwable $th) {
            return $this->respond(new ActionPayload(401, ['Error' => $th->getMessage(), 'ErrorType' => $th->__toString()]))->withStatus(401);
            //return $this->respond(new ActionPayload(401, ['Error' => 'O nome de usuário ou email escolhido já foi utilizado']))->withStatus(401);
        }
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email not valid',
            'username' => 'A valid username must be provided',
            'password' => 'Password must contain at least 6 characters with at least one uppercase letter, one lower case letter and a symbol',
            'passwordConfirmation' => 'Password confirmation doesn\'t match.',
        ];
    }

    public function rules(): ?array
    {
        $parsedBody = $this->request->getParsedBody();
        $password = $parsedBody['password'] ?? '';

        return [
            'email' => Validator::email(),
            'username' => Validator::alnum()->noWhitespace()->length(6, 20),
            'password' => function ($value) { return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value); },
            'passwordConfirmation' => fn ($value) => $value === $password,
        ];
    }
}
