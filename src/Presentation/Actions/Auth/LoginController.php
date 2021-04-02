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
        $this->loginService->auth($credentials);

        return $this->response;
    }

    public function messages(): array
    {
        return [
            'email' => 'Email not valid',
        ];
    }

    protected function rules(): ?array
    {
        return [
            'email' => Validator::email(),
            'username' => Validator::alnum(),
            'password' => function ($value) { return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value); },
        ];
    }
}
