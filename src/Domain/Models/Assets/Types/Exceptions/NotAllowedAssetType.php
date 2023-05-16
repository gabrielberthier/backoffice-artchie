<?php

namespace App\Domain\Models\Assets\Types\Exceptions;

use App\Domain\Exceptions\Protocols\DomainException as ProtocolsDomainException;


class NotAllowedAssetType extends ProtocolsDomainException
{
  public function __construct()
  {
    $this->message = "Asset type not found or supported";
  }
}
