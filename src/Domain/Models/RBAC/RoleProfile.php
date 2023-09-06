<?php

namespace App\Domain\Models\RBAC;

use App\Domain\Models\Account;
use DateTimeInterface;

class RoleProfile
{
    /** @var Role[] */
    public array $roles;
    public DateTimeInterface $createdAt;
    public DateTimeInterface $updatedAt;

    public function __construct(public Account $account)
    {
        $this->roles = [];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addRole(Role $role)
    {
        array_push($this->roles, $role);
    }

    public function canAccess(Resource $resource, ContextIntent|Permission $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->canAcess($resource, $permission)) {
                return true;
            }
        }

        return false;
    }
}