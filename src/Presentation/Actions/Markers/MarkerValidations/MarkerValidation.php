<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class MarkerValidation
{
    public function validation(): AbstractRule
    {
        $assetValidation = new AssetValidation();

        return v::key('marker_name', v::alnum('$', '*', '-', '#', '&', ' ', '.'))->
            key('marker_text', v::stringType())->
            key('marker_title', v::stringType())->
            key(
                'asset',
                $assetValidation->validation(),
                false
            );
    }
}
