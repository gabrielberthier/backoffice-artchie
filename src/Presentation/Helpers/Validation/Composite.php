<?php

namespace App\Presentation\Helpers\Validation;

use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ErrorBag;
use App\Presentation\Protocols\Validation;

class Composite implements Validation
{
    /**
     * @var Validation[]
     */
    private array $compositions = [];

    private ErrorBag $errorBag;

    public function __construct()
    {
        $this->errorBag = new ErrorBag();
    }

    public function pushValidation(Validation $validation): self
    {
        $this->compositions[] = $validation;

        return $this;
    }

    public function validate($input): ?ValidationError
    {
        foreach ($this->compositions as $validation) {
            $error = $validation->validate($input);
            if ($error) {
                $this->errorBag->push($error);
            }
        }

        return $this->errorBag->hasErrors() ? $this->errorBag : null;
    }
}
