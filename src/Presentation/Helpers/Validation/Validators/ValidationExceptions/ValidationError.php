<?php

namespace App\Presentation\Helpers\Validation\Validators\ValidationExceptions;

use Exception;

class ValidationError extends Exception
{
    private $field = '';

    public function forField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get the value of field.
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set the value of field.
     *
     * @param mixed $field
     *
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}
