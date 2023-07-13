<?php

namespace App\Presentation\Helpers\Validation\Validators\Composite;

use App\Presentation\Helpers\Validation\Validators\Interfaces\ValidationInterface;
use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ErrorBag;
use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ValidationError;

class Composite implements ValidationInterface
{
    /**
     * @var ValidationInterface[]
     */
    private array $compositions = [];

    private ErrorBag $errorBag;

    public function __construct()
    {
        $this->errorBag = new ErrorBag();
    }

    public function pushValidation(ValidationInterface $validation): self
    {
        $this->compositions[] = $validation;

        return $this;
    }

    public function validate($input): ?ValidationError
    {
        foreach ($this->compositions as $validation) {
            $error = $validation->validate($input);
            if ($error instanceof ValidationError) {
                $this->errorBag->push($error);
            }
        }

        return $this->errorBag->hasErrors() ? $this->errorBag : null;
    }


    /**
     * @return ValidationInterface[]
     */
    public function getValidations(): array
    {
        return $this->compositions;
    }

    public function getErrorBag()
    {
        return $this->errorBag;
    }
}