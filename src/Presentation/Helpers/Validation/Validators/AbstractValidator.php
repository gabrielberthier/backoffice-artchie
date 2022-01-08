<?php

namespace App\Presentation\Helpers\Validation\Validators;

use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;

abstract class AbstractValidator implements Validation
{
    protected string $field;
    protected ?string $message;

    public function validate($input): ?ValidationError
    {
        if (array_key_exists($this->field, $input)) {
            $subject = $input[$this->field];
            if ($this->makeValidation($subject)) {
                return null;
            }
        }
        $message = $this->message ?? "{$this->field} does not match the defined requirements";

        return (new ValidationError($message))->forField($this->field);
    }

    abstract protected function makeValidation($subject): bool;
}
