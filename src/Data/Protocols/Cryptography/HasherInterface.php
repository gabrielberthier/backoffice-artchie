<?php

namespace App\Data\Protocols\Cryptography;

interface HasherInterface
{
  public static function hash(string $password, array $options = []);
}
