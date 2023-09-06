<?php
namespace App\Infrastructure\Persistence\Cycle\RbacDb;

use App\Data\Entities\Cycle\Rbac\CycleRole;
use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Role;
use Cycle\ORM\ORM;

class CycleRoleAccessRepository
{
    public function __construct(private ORM $orm)
    {
    }

    public function getRoleWithPermissions(Role $role, Resource $resource)
    {
        $role = $this->orm
            ->getRepository(CycleRole::class)
            ->select()
            ->distinct()
            ->with("permissions")
            ->where(
                "permissions.isActive",
                true
            )
            ->andWhere("permissions.resource.name", $resource->name);
    }
}