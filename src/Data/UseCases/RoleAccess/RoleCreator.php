<?php
use App\Domain\Models\RBAC\AccessControl;

interface RoleStorer
{
    function store(Role $role): void;
}

class RoleCreator
{
    public function __construct(public AccessControl $accessControl)
    {

    }
    public function create(
        string $roleName,
        string $description = ""
    ) {
        $role = $this->accessControl->forgeRole($roleName, $description)->getRole($roleName);
    }
}