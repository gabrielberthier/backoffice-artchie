<?php

namespace App\Presentation\Helpers\Validation\Validators\Adapters;

use App\Presentation\Helpers\Validation\Validators\Interfaces\AbstractValidator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validatable;

class AwesomeValidationAdapter extends AbstractValidator
{
    public function __construct(
        protected string $field,
        protected Validatable $rule,
        protected ?string $message = null
    ) {
    }

    protected function makeValidation($subject): bool
    {
        try {
            $this->rule->assert($subject);

            return true;
        } catch (NestedValidationException $nestedValidationException) {
            $this->message ??= $nestedValidationException->getFullMessage();

            return false;
        }
    }
}