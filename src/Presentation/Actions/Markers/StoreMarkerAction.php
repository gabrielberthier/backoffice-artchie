<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Domain\Models\Marker\Marker;
use App\Presentation\Actions\Markers\MarkerBuilder\MarkerBuilder;
use App\Presentation\Actions\Markers\MarkerValidations\MarkerValidation;
use App\Presentation\Actions\Markers\MarkerValidations\PlacementObjectValidation;
use App\Presentation\Actions\Protocols\Action;
use Doctrine\Common\Collections\ArrayCollection;
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
        $markerAsset = null;
        $placementObject = null;

        if (isset($marker['asset'])) {
            $markerAsset = $builder->prepareAsset($marker['asset']);
        }

        if (isset($parsedBody['pose_object'])) {
            $placementObject = $builder->makePlacementObject($parsedBody['pose_object']);
        }

        if (is_object($marker)) {
            $body = (array) $marker;
        }
        
        [
            "marker_name" => $name,
            "marker_text" => $text,
            "marker_title" => $title,
        ] = $body;

        $this->markerServiceStore->insert(
            $museumId,
            new Marker(
                null,
                null,
                name: $name,
                text: $text,
                title: $title,
                resources: new ArrayCollection([$placementObject]),
                asset: $markerAsset
            )
        );

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