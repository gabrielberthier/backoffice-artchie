<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class AssetValidation
{
    public function validation(): AbstractRule
    {
        return v::key('file_name', v::fileRule())
            ->key('path', v::stringType())
            ->key('url', v::optional(v::url()))
            ->key('original_name', v::StringType());
    }
}
