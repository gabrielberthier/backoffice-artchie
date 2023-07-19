<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Museum;

use App\Domain\Models\Museum;
use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class CreateMuseumAction extends Action
{
    public function __construct(
        private MuseumRepository $museumRepository
    ) {
    }

    public function action(Request $request): Response
    {
        $parsedBody = $request->getParsedBody();
        $parsedBody['name'] = htmlspecialchars($parsedBody['name']);
        $museum = new Museum(null, ...$parsedBody);

        $this->museumRepository->add($museum);

        return $this->respondWithData(['message' => 'Success! Museum created', 'museum' => $museum]);
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email is not valid',
            'name' => 'Incorrect name provided',
        ];
    }

    /**
     * Summary of rules
     *
     * @return array<\Respect\Validation\ChainedValidator>
     */
    public function rules(Request $request): ?array
    {
        return [
            'email' => Validator::email(),
            'name' => Validator::StringType(),
        ];
    }
}
