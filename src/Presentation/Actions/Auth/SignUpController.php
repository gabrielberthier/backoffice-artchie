<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Data\Protocols\Cryptography\HasherInterface;
use App\Domain\Dto\AccountDto;
use App\Presentation\Actions\Auth\Utilities\CookieTokenManager;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class SignUpController extends Action
{
    private CookieTokenManager $cookieManager;

    public function __construct(
        private SignUpServiceInterface $service,
        private HasherInterface $hasherInterface
    ) {
        $this->cookieManager = new CookieTokenManager();
    }

    public function action(Request $request): Response
    {
        $parsedBody = $request->getParsedBody();
        [
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ] = $parsedBody;

        $password = $this->hasherInterface->hash($password);
        $account = new AccountDto(email: $email, username: $username, password: $password);
        $tokenize = $this->service->register($account);
        $refreshToken = $tokenize->renewToken;

        $this->cookieManager->implant($refreshToken);

        return $this
            ->respondWithData(['token' => $tokenize->token])
            ->withStatus(201, 'Created token');
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email not valid',
            'username' => 'A valid username must be provided',
            'password' => 'Password must contain at least 6 characters with at least one uppercase letter, one lower case letter and a symbol',
            'passwordConfirmation' => "Password confirmation doesn't match.",
        ];
    }

    /**
     * Summary of rules
     *
     * @return array
     */
    public function rules(Request $request): ?array
    {
        $parsedBody = $request->getParsedBody();
        $password = $parsedBody['password'] ?? '';

        return [
            'email' => Validator::email(),
            'username' => Validator::alnum()->noWhitespace()->length(6, 20),
            'password' => static function ($value) {
                return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/m', $value);
            },
            'passwordConfirmation' => static fn($value) => $value === $password,
        ];
    }
}