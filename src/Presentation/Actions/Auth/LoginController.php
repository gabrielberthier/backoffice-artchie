<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Protocols\Validation;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController extends Action
{
    private Credentials $credentials;

    public function __construct(
        private LoginServiceInterface $loginService,
        private Validation $validator
    ) {
    }

    public function action(): Response
    {
        [
            'email' => $email,
            'username' => $username,
            'password' => $password
        ] = $this->request->getParsedBody();

        if (empty($username) || empty($email)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->validate($this->request->getParsedBody());

        $this->credentials = new Credentials($email, $username, $password);
        $this->loginService->auth($this->credentials);

        return $this->response;
    }

    protected function validate(null | array | object $body)
    {
        $this->validator->validate($body);
    }
}
