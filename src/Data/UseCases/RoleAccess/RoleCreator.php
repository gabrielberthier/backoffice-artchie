<?php
use App\Domain\Models\RBAC\AccessControl;
use App\Domain\Models\RBAC\Role;
use App\Domain\OptionalApi\Result;

interface RoleStorer
{
    function store(Role $role): Result;
}

class RoleCreator
{
    public function __construct(
        public AccessControl $accessControl,
        public RoleStorer $roleStorer
    ) {

    }
    public function create(
        string $roleName,
        string $description = ""
    ) {
        $role = $this->accessControl->forgeRole($roleName, $description)->getRole($roleName);

        return $this->roleStorer->store($role);
    }
}