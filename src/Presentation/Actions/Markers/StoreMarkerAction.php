<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Presentation\Actions\Markers\MarkerBuilder\MarkerBuilder;
use App\Presentation\Actions\Markers\MarkerValidations\MarkerValidation;
use App\Presentation\Actions\Markers\MarkerValidations\PlacementObjectValidation;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class StoreMarkerAction extends Action
{
    public function __construct(
        private MarkerServiceStoreInterface $markerServiceStore,
        private MarkerBuilder $markerBuilder
    ) {
    }

    public function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();
        $museumId = $parsedBody['museum_id'] ?? null;

        $builder = $this->markerBuilder;

        $builder->prepareMarker($parsedBody['marker']);

        if (isset($parsedBody['marker_asset'])) {
            $builder->appendMarkerAsset($parsedBody['marker_asset']);
        }

        if (isset($parsedBody['posed_object'])) {
            $builder->appendResource($parsedBody['posed_object']);
        }

        $marker = $builder->getMarker();

        $marker = $this->markerServiceStore->insert($museumId, $marker);

        return $this->respondWithData(['message' => 'Success! Marker created', 'Marker' => $marker]);
    }

    public function messages(): ?array
    {
        return [
            'museum_id' => 'Museum id must be an integer value',
            'marker' => 'A marker must have mandatory marker_name, marker_text, marker_title and may have an optional asset',
            'pose_object' => '(Optional) A pose_object must be set with pose_object_name and may have an optional asset',
        ];
    }

    public function rules(): ?array
    {
        $markerValidation = new MarkerValidation();
        $posedObjectValidation = new PlacementObjectValidation();

        return [
            'museum_id' => Validator::intType(),
            'marker' => $markerValidation->validation(),
            'pose_object' => $posedObjectValidation->validation(),
        ];
    }
}
