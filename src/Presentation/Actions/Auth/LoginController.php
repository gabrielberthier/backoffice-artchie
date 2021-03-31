<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

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
        $errors = $this->validator->validate($parsedBody);

        if (empty($username) || empty($email)) {
            $this->response = $this->response->withStatus(400);
        }

        if ($errors === null) {
            $credentials = new Credentials($email, $username, $password);
            $this->loginService->auth($credentials);

            return $this->response;
        }

        throw new HttpBadRequestException($this->request, 'Invalid email');
    }

    protected function validate(null | array | object $body): ?ValidationError
    {
        return ($this->validator->validate($body));
    }
}
