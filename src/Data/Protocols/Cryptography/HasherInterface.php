<?php

namespace App\Data\Protocols\Cryptography;

interface HasherInterface
{
  public function hash(string $password, array $options = []);
}
