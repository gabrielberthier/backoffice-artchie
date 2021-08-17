<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class PlacementObjectValidation
{
    public function validation(): AbstractRule
    {
        $assetValidation = new AssetValidation();

        return v::optional(v::allOf(
            v::key('pose_object_name', v::alnum('$', '*', '-', '#', '&', ' ', '.')),
            v::key(
                'asset',
                v::optional($assetValidation->validation()),
            )
        ));
    }
}
