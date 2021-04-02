<?php

namespace App\Presentation\Helpers\Validation\Validators;

use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;

class CallbackValidationAdapter implements Validation
{
    public function __construct(private string $field, private $rule, private string $message)
    {
    }

    public function validate($input): ?ValidationError
    {
        $subject = $input[$this->field];
        if (is_callable($this->rule)) {
            $rule = $this->rule;
            if ($rule($subject)) {
                return null;
            }
        }

        return new ValidationError($this->message);
    }
}
