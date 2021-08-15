<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class PlacementObjectValidation
{
    public function validation(): AbstractRule
    {
        return v::allOf(
            v::key('pose_object_name', v::stringType()),
            (new AssetValidation())->validation()
        );
    }
}
