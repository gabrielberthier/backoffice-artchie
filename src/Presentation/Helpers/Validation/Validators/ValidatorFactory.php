<?php

namespace App\Presentation\Helpers\Validation\Validators;

use Respect\Validation\Rules\AbstractRule;

class ValidatorFactory
{
    public function create(mixed $validation, string $key, ?string $message = null): ?AbstractValidator
    {
        if ($validation instanceof AbstractRule) {
            return new AwesomeValidationAdapter($key, $validation, $message);
        }
        if (is_callable($validation)) {
            return new CallbackValidationAdapter($key, $validation, $message);
        }

        return null;
    }
}
