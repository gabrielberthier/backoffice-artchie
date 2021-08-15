<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class AssetValidation
{
    public function validation(): AbstractRule
    {
        return v::optional(v::key(
            'asset',
            v::key('file_name', v::file())
                ->key('media_type', v::stringType())
                ->key('path', v::stringType())
                ->key('url', v::optional(v::url()))
        ));
    }
}
