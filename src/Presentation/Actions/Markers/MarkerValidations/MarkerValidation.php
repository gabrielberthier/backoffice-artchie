<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class MarkerValidation
{
    public function validation(): AbstractRule
    {
        return v::allOf(
            v::key('marker_name', v::stringType()),
            v::key('marker_text', v::stringType()),
            v::key('marker_title', v::stringType()),
            (new AssetValidation())->validation()
        );
    }
}
