<?php
namespace App\Data\Protocols\Rbac;

use App\Domain\Models\RBAC\Role;
use App\Domain\OptionalApi\Option;

interface RoleFetcherInterface
{
    /** @return Option<Role> */
    public function getRole(string $role): Option;
}