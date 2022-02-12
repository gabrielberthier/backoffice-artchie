<?php

namespace App\Presentation\Helpers\Validation\Validators\Interfaces;

use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ValidationError;

abstract class AbstractValidator implements ValidationInterface
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
