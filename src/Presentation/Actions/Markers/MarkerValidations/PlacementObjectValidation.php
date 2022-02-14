<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Validator as v;

class PlacementObjectValidation
{
    public function validation(): array
    {
        $assetValidation = new AssetValidation();

        return [
            'pose_object_name' => v::alnum('$', '*', '-', '#', '&', ' ', '.'),
            'asset' => ($assetValidation->validation())
        ];
    }
}
