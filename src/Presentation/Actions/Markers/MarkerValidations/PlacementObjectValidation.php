<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class PlacementObjectValidation
{
    public function validation(): AbstractRule
    {
        $assetValidation = new AssetValidation();

        return v::optional(v::arrayVal())
            ->key('pose_object_name', v::alnum('$', '*', '-', '#', '&', ' '), false)
            ->key(
                'asset',
                $assetValidation->validation(),
                false
            )
            ;
    }
}
