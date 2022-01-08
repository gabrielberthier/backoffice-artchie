<?php

namespace App\Presentation\Helpers\Validation;

use App\Presentation\Helpers\Validation\Validators\ValidatorFactory;
use App\Presentation\Protocols\Validation;

class ValidationFacade
{
    public function __construct(private array $rules, private array $messages = [])
    {
    }

    public function createValidations(): Validation
    {
        $composite = new Composite();
        $validatorFactory = new ValidatorFactory();

        foreach ($this->rules as $key => $validation) {
            $message = $this->messages[$key] ?? null;
            $validationRule = $validatorFactory->create($validation, $key, $message);

            if ($validationRule) {
                $composite->pushValidation($validationRule);
            }
        }

        return $composite;
    }
}
