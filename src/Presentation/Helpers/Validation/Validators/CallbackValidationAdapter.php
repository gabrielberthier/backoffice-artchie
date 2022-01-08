<?php

namespace App\Presentation\Helpers\Validation\Validators;

class CallbackValidationAdapter extends AbstractValidator
{
    public function __construct(protected string $field, protected $rule, protected ?string $message = null)
    {
    }

    protected function makeValidation($subject): bool
    {
        $rule = $this->rule;

        return is_callable($rule) && $rule($subject);
    }
}
