<?php

namespace App\Presentation\Protocols;

use App\Presentation\Helpers\Validation\ValidationError;

interface Validation
{
    public function validate($input): ?ValidationError;
}
