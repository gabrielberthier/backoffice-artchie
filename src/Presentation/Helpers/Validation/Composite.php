<?php

use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;

class Composite implements Validation
{
  /**
   * @var Validation[]
   */
  private array $compositions = [];

  public function pushValidation(Validation $validation): self
  {
    $compositions[] = $validation;
    return $this;
  }

  public function validate($input): ?ValidationError
  {
    foreach ($this->compositions as $validation) {
      $error = $validation->validate($input);
      if ($error) {
        return $error;
      }
    }
    return null;
  }
}
