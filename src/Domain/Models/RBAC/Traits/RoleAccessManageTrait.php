<?php
namespace App\Domain\Models\RBAC\Traits;

use App\Domain\Models\RBAC\Role;
use App\Domain\Models\RBAC\Utilities\ExtractNameUtility;

trait RoleAccessManageTrait
{
    /** @var array<string, Role> */
    public array $roles = [];
    public function forgeRole(string $roleName, string $description = ''): self
    {
        $this->roles[$roleName] = new Role(
            $roleName,
            $description
        );

        return $this;
    }

    public function getRole(Role|string $role): Role
    {
        return $this->roles[ExtractNameUtility::extractName($role)];
    }

    public function getRoles()
    {
        return array_values($this->roles);
    }

    public function revokeRole(Role|string $role): void
    {
        $this->roles[ExtractNameUtility::extractName($role)]->inactivate();
    }

    public function extendRole(Role|string $targetRole, Role|string ...$roles)
    {
        $target = $this->roles[ExtractNameUtility::extractName($targetRole)];
        foreach ($roles as $includer) {
            $ref = ExtractNameUtility::extractName($includer);
            if (!in_array($ref, $this->roles)) {
                continue;
            }
            $target->extendRole($this->roles[$ref]);
        }
    }
}