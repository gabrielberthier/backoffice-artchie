<?php

namespace App\Presentation\Helpers\Validation\Validators\Factories;

use App\Presentation\Helpers\Validation\Validators\Adapters\AwesomeValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\Adapters\CallbackValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\Adapters\NestedValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\Interfaces\AbstractValidator;
use Respect\Validation\Rules\AbstractRule;
use Closure;

class ValidatorFactory
{
    public function create(mixed $validation, string $key, ?string $message = null): ?AbstractValidator
    {
        if (is_array($validation)) {
            $message = (is_array($message)) ? $message : [];
            $nestedValidationAdapter = new NestedValidationAdapter($key);
            foreach ($validation as $key => $value) {
                $nestedMessage = $this->messages[$key] ?? null;
                $nestedValidation = $this->create($value, $key, $nestedMessage);
                $nestedValidationAdapter->pushValidation($nestedValidation);
            }

            return $nestedValidationAdapter;
        } else if ($validation instanceof AbstractRule) {
            return new AwesomeValidationAdapter($key, $validation, $message);
        } else if (is_callable($validation)) {
            $closureValidation = Closure::fromCallable($validation);
            return new CallbackValidationAdapter($key, $closureValidation, $message);
        }

        return null;
    }
}
