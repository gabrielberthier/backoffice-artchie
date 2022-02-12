<?php

namespace App\Presentation\Helpers\Validation\Validators\Adapters;

use App\Presentation\Helpers\Validation\Validators\Interfaces\AbstractValidator;
use Respect\Validation\Rules\AbstractRule;

class AwesomeValidationAdapter extends AbstractValidator
{
    public function __construct(
        protected string $field,
        protected AbstractRule $rule,
        protected ?string $message = null
    ) {
    }

    protected function makeValidation($subject): bool
    {
        return $this->rule->validate($subject);
    }
}
