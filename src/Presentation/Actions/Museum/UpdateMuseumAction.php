<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Museum;

use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class UpdateMuseumAction extends Action
{
    public function __construct(
        private MuseumRepository $museumRepository
    ) {
    }

    public function action(Request $request): Response
    {
        $parsedBody = $request->getParsedBody();
        $name = $parsedBody['name'] ?? '';
        $parsedBody['name'] = htmlspecialchars($name);

        $museum = $this->museumRepository->update((int) $this->resolveArg('id'), $parsedBody);

        return $this->respondWithData(['message' => 'Success! Museum updated', 'museum' => $museum]);
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email is not valid',
            'name' => 'Incorrect name provided',
        ];
    }

    public function rules(Request $request): ?array
    {
        return [
            'email' => Validator::optional(Validator::email()),
            'name' => Validator::optional(Validator::StringType()),
        ];
    }
}