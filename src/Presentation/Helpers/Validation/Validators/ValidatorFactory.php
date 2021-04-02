<?php

namespace App\Presentation\Helpers\Validation\Validators;

use Respect\Validation\Rules\AbstractRule;

class ValidatorFactory
{
    public function create(mixed $validation, string $key, ?string $message = null): ?AbstractValidator
    {
        if ($message) {
            return $this->createWithMessage($validation, $key, $message);
        }

        return $this->createWithoutMessage($validation, $key);
    }

    private function createWithMessage(mixed $validation, string $key, string $message): ?AbstractValidator
    {
        if ($validation instanceof AbstractRule) {
            return new AwesomeValidationAdapter($key, $validation, $message);
        }
        if (is_callable($validation)) {
            return new CallbackValidationAdapter($key, $validation, $message);
        }

        return null;
    }

    private function createWithoutMessage(mixed $validation, string $key): ?AbstractValidator
    {
        if ($validation instanceof AbstractRule) {
            return new AwesomeValidationAdapter($key, $validation);
        }
        if (is_callable($validation)) {
            return new CallbackValidationAdapter($key, $validation);
        }

        return null;
    }
}
