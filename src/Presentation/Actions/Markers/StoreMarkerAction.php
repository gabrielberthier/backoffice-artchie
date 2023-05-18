<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Presentation\Actions\Markers\MarkerBuilder\MarkerBuilder;
use App\Presentation\Actions\Markers\MarkerValidations\MarkerValidation;
use App\Presentation\Actions\Markers\MarkerValidations\PlacementObjectValidation;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class StoreMarkerAction extends Action
{
    public function __construct(
        private MarkerServiceStoreInterface $markerServiceStore,
        private MarkerBuilder $markerBuilder
    ) {
    }

    public function action(Request $request): Response
    {
        $parsedBody = $request->getParsedBody();
        $museumId = $parsedBody['museum_id'] ?? null;
        $builder = $this->markerBuilder;
        $marker = $parsedBody['marker'];
        $builder->prepareMarker($marker);

        if (isset($marker['asset'])) {
            $builder->appendMarkerAsset($marker['asset']);
        }

        if (isset($parsedBody['pose_object'])) {
            $builder->appendResource($parsedBody['pose_object']);
        }

        $marker = $builder->getMarker();
        $this->markerServiceStore->insert($museumId, $marker);

        return $this->respondWithData(['message' => 'Success! Marker created']);
    }

    public function messages(): ?array
    {
        return [
            'museum_id' => 'Museum id must be an integer value',
            'marker' => 'A marker must have mandatory marker_name, marker_text, marker_title and may have an optional asset',
            'pose_object' => '(Optional) A pose_object must be set with pose_object_name and may have an optional asset',
        ];
    }


    /**
     * Summary of rules
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * 
     * @return array
     */
    public function rules(Request $request): ?array
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
