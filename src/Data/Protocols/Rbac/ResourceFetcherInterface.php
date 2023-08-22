<?php

namespace App\Data\Protocols\Rbac;

use PhpOption\Option;
use App\Domain\Models\RBAC\Resource;

interface ResourceFetcherInterface
{
    /** @return Option<Resource> */
    public function getResource(string $resource): Option;
}