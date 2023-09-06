<?php
namespace App\Domain\Models\RBAC;

use App\Domain\Models\RBAC\Traits\{
    ResourceAccessManageTrait,
    RoleAccessManageTrait
};
use App\Domain\Models\RBAC\Utilities\ExtractNameUtility;

/**
 * AccessControl is a Facade to interact with Roles available in the system.
 * It will handle most authorization functions and 
 */
class AccessControl implements \JsonSerializable
{
    use ResourceAccessManageTrait, RoleAccessManageTrait;

    public function addPermissionToRole(
        Role|string $role,
        Resource|string $resource,
        ContextIntent $intent,
        string $permissionName = ""
    ): self {
        $permission = $permissionName === "" ?
            Permission::makeWithPreferableName($intent, $resource) :
            new Permission($permissionName, $intent);

        $roleRef = ExtractNameUtility::extractName($role);
        $resourceRef = ExtractNameUtility::extractName($resource);
        $this->roles[$roleRef]->addPermissionToResource(
            $permission,
            $this->resources[$resourceRef]
        );

        return $this;
    }

    /**
     * @param Role|string $role
     * @param Resource $permission
     * @param Permission[] $permission
     */
    public function grantAccessOn(
        Role|string $role,
        Resource $resource,
        array $permissions
    ): self {
        $roleRef = ExtractNameUtility::extractName($role);
        foreach ($permissions as $permission) {
            $this->roles[$roleRef]->addPermissionToResource(
                $permission,
                $resource
            );
        }

        return $this;
    }

    public function constructObject(): string
    {
        return json_encode($this);
    }

    public function jsonSerialize(): mixed
    {
        return [
            "roles" => $this->roles,
        ];
    }

    public function tryAccess(
        Role|string $role,
        Resource|string $resource,
        ContextIntent|Permission $permission,
        ?callable $fallback = null
    ): bool {
        $result = $this
            ->getResource($resource)
            ->map(
                fn(Resource $resource): bool => $this
                    ->getRole($role)
                    ->map(
                        static fn(Role $role): bool => $role->canAcess(
                            $resource,
                            $permission
                        )
                    )->get()
            )
            ->getOrElse(false);

        if (!$result) {
            return is_null($fallback)
                ? false
                : $fallback($role, $resource, $permission);
        }

        return true;
    }
}