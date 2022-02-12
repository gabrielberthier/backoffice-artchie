<?php

namespace App\Presentation\Helpers\Validation\Validators;

use App\Presentation\Helpers\Validation\Validators\Interfaces\ValidationInterface;
use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ValidationError;
use Ramsey\Uuid\Validator\ValidatorInterface;

class EmailValidation implements ValidationInterface
{
    public function __construct(private string $field, private ValidatorInterface $validator)
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
