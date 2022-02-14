<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class MarkerValidation
{
    public function validation(): array
    {
        $assetValidation = new AssetValidation();

        return [
            'marker_name' => v::alnum('$', '*', '-', '#', '&', ' ', '.'),
            'marker_text' => v::stringType(),
            'marker_title' => v::stringType(),
            'asset' => $assetValidation->validation(),
        ];
    }
}
