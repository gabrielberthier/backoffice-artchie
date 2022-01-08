<?php

namespace App\Presentation\Helpers\Validation\Validators;

use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;
use Respect\Validation\Validator;

class EmailValidation implements Validation
{
    public function __construct(private string $field, private Validator $validator)
    {
    }

    public function validate($input): ?ValidationError
    {
        if ($this->validator->validate($input[$this->field])) {
            return null;
        }

        return new ValidationError();
    }
}
