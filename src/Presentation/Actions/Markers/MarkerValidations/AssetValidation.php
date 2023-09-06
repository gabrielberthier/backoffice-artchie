<?php

namespace App\Presentation\Actions\Markers\MarkerValidations;

use App\Presentation\Helpers\Validation\Rules\FileRule;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as v;

class AssetValidation
{
    public function validation(): Validatable
    {
        return v::key('file_name', new FileRule())
            ->key('path', v::stringType())
            ->key('url', v::optional(v::url()))
            ->key('original_name', v::StringType());
    }
}