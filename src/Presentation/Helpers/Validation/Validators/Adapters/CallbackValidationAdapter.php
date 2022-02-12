<?php

namespace App\Presentation\Helpers\Validation\Validators\Adapters;

use Closure;
use App\Presentation\Helpers\Validation\Validators\Interfaces\AbstractValidator;

class CallbackValidationAdapter extends AbstractValidator
{
    public function __construct(
        protected string $field,
        protected Closure $rule,
        protected ?string $message = null
    ) {
    }

    protected function makeValidation($subject): bool
    {
        $rule = $this->rule;

        return $rule($subject);
    }
}
