<?php

namespace App\Presentation\Helpers\Validation;

use App\Presentation\Helpers\Validation\Validators\AwesomeValidationAdapter;
use App\Presentation\Helpers\Validation\Validators\CallbackValidationAdapter;
use App\Presentation\Protocols\Validation;
use Respect\Validation\Rules\AbstractRule;

class ValidationFacade
{
    public function __construct(private array $rules, private array $messages = [])
    {
    }

    public function createValidations(): Validation
    {
        $composite = new Composite();
        $validationRule = null;
        foreach ($this->rules as $key => $validation) {
            $message = $messages[$key] ?? '';
            if ($validation instanceof AbstractRule) {
                $validationRule = new AwesomeValidationAdapter($key, $validation, $message);
            } elseif (is_callable($validation)) {
                $validationRule = new CallbackValidationAdapter($key, $validation, $message);
            }

            if ($validationRule) {
                $composite->pushValidation($validationRule);
            }
        }

        return $composite;
    }
}
