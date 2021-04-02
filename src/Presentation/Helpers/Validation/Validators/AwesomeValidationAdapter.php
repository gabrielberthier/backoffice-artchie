<?php

namespace App\Presentation\Helpers\Validation\Validators;

use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;
use Respect\Validation\Rules\AbstractRule;

class AwesomeValidationAdapter implements Validation
{
    public function __construct(private string $field, private AbstractRule $rule, private string $message)
    {
    }

    public function validate($input): ?ValidationError
    {
        if (array_key_exists($this->field, $input)) {
            $subject = $input[$this->field];
            if ($this->rule->validate($subject)) {
                return null;
            }
        }

        return new ValidationError($this->message);
    }
}
