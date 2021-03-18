<?php

namespace App\Data\Protocols\Cryptography;

interface ComparerInterface
{
  public static function compare(string $password, string $hash);
}
