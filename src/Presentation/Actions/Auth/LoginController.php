<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController extends Action
{
    private Credentials $credentials;

    public function __construct(private LoginServiceInterface $loginService)
    {
    }

    public function action(): Response
    {

        [
            'email' => $email,
            'username' => $username,
            'password' => $password
        ] = $this->request->getParsedBody();

        $this->credentials = new Credentials($email, $username, $password);
        $this->loginService->auth($this->credentials);
        return $this->response;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }
}
