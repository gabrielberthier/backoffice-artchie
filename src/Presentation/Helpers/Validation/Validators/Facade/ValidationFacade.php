<?php

namespace App\Presentation\Helpers\Validation\Validators\Facade;

use App\Presentation\Helpers\Validation\Validators\Composite\Composite;
use App\Presentation\Helpers\Validation\Validators\Factories\ValidatorFactory;
use App\Presentation\Helpers\Validation\Validators\Interfaces\AbstractValidator;
use App\Presentation\Helpers\Validation\Validators\Interfaces\ValidationInterface;


class ValidationFacade
{
    public function __construct(private array $rules, private array $messages = [])
    {
    }

    public function createValidations(): ValidationInterface
    {
        $composite = new Composite();
        $validatorFactory = new ValidatorFactory();

        foreach ($this->rules as $key => $validation) {
            $message = $this->messages[$key] ?? null;
            $validationRule = $validatorFactory->create($validation, $key, $message);

            if ($validationRule instanceof AbstractValidator) {
                $composite->pushValidation($validationRule);
            }
        }

        return $composite;
    }
}