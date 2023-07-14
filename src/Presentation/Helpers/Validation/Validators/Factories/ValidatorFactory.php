<?php

namespace App\Presentation\Helpers\Validation\Validators\Factories;

use Closure;
use Respect\Validation\Validatable;
use App\Presentation\Helpers\Validation\Validators\Adapters\AwesomeValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\Adapters\CallbackValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\Adapters\NestedValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\Interfaces\AbstractValidator;

class ValidatorFactory
{
    public function create(mixed $validation, string $key, string|array|null $message = null): ?AbstractValidator
    {
        if (is_array($validation)) {
            $nestedValidationAdapter = new NestedValidationAdapter($key);
            foreach ($validation as $key => $value) {
                $nestedMessage = $message;
                if (is_array($message)) {
                    $nestedMessage = $message[$key] ?? null;
                }
                $nestedValidation = $this->create($value, $key, $nestedMessage);
                $nestedValidationAdapter->pushValidation($nestedValidation);
            }

            return $nestedValidationAdapter;
        }
        if ($validation instanceof Validatable) {
            return new AwesomeValidationAdapter($key, $validation, $message);
        }
        if (is_callable($validation)) {
            $closureValidation = Closure::fromCallable($validation);
            return new CallbackValidationAdapter($key, $closureValidation, $message);
        }

        return null;
    }
}
