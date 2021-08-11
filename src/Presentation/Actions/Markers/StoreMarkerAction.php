<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Domain\Models\Museum;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class StoreMarkerAction extends Action
{
    public function __construct(
        private MarkerServiceStoreInterface $markerServiceStore
    ) {
    }

    public function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();
        $parsedBody['name'] = htmlspecialchars($parsedBody['name']);
        $museum = new Museum(...$parsedBody);

        $this->museumRepository->insert($museum);

        return $this->respondWithData(['message' => 'Success! Museum created', 'museum' => $museum]);
    }

    public function messages(): ?array
    {
        return [
            'email' => 'Email is not valid',
            'name' => 'Incorrect name provided',
        ];
    }

    public function rules(): ?array
    {
        return [
            'email' => Validator::email(),
            'name' => Validator::StringType(),
            'placementObjects' => Validator::allOf(
                Validator::arrayVal(),
                Validator::key('field1', Validator::stringType())
            ),
        ];
    }
}
