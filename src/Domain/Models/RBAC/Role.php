<?php

namespace App\Domain\Models\RBAC;

use DateTimeInterface;
use JsonSerializable;
use WeakMap;

class Role implements JsonSerializable
{
    /** @var WeakMap<Resource, Permission[]> */
    public WeakMap $keyMap;
    /** @var Role[] */
    public readonly array $extendedRoles;
    public DateTimeInterface $createdAt;
    public DateTimeInterface $updatedAt;

    public function __construct(
        public readonly string $name,
        public readonly string $description,
        private bool $isActive = true,
    ) {
        $this->keyMap = new WeakMap();
        $this->extendedRoles = [];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addPermissionToResource(
        Permission $permission,
        Resource $resource
    ): void {
        if (!$this->keyMap->offsetExists($resource)) {
            $this->keyMap[$resource] = [];
        }

        $this->keyMap[$resource][] = $permission;
    }

    public function extendRole(Role $role)
    {
        $this->extendedRoles[] = $role;
    }

    public function getPermissionsFromResource(Resource $resource): array
    {
        return $this->keyMap[$resource];
    }

    public function canAcess(Resource $resource, Permission|ContextIntent $permission): bool
    {
        if ($this->isActive) {
            $permissionSet =
                $this->keyMap->offsetExists($resource) ?
                $this->keyMap->offsetGet($resource) :
                null;

            if ($permissionSet) {
                foreach ($permissionSet as $rolePermission) {
                    if ($rolePermission->satisfies($permission)) {
                        return true;
                    }
                }
            }

            foreach ($this->extendedRoles as $upper) {
                if ($upper->canAcess($resource, $permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function inactivate()
    {
        $this->isActive = false;
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function jsonSerialize(): mixed
    {
        $resourcesMap = [];
        foreach ($this->keyMap as $resource => $permissions) {
            $resourcesMap[$resource->name] = [
                'permissions' => join(
                    ',',
                    array_map(fn(Permission $p) => $p->name, $permissions)
                )
            ];
        }
        return [
            'name' => $this->name,
            'is_active' => $this->isActive,
            'extends' => join(",", array_map(fn(Role $parent) => $parent->name, $this->extendedRoles)),
            'resources' => $resourcesMap
        ];
    }
}