<?php

namespace App\Presentation\Helpers\Validation\Validators\Interfaces;

use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ValidationError;

interface ValidationInterface
{
    public function validate($input): ?ValidationError;
}
