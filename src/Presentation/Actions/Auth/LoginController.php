<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;

class LoginController extends Action
{
    public function __construct(
        private LoginServiceInterface $loginService,
        private Validation $validator
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
        $this->loginService->auth($credentials);

        return $this->response;
    }

    public function rules(): array
    {
        return [
            'email' => v::email(),
            'username' => v::alnum(),
            'password' => fn ($value) => preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value),
        ];
    }

    public function messages(): array
    {
        return [
            'email' => 'Email not valid',
        ];
    }

    protected function validate(null | array | object $body): ?ValidationError
    {
        return $this->validator->validate($body);
    }
}
