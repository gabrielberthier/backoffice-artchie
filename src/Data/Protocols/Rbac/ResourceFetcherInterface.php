<?php

namespace App\Data\Protocols\Rbac;

use App\Domain\OptionalApi\Option;
use App\Domain\Models\RBAC\Resource;

interface ResourceFetcherInterface
{
    /** @return Option<Resource> */
    public function getResource(string $role): Option;
}