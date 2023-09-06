<?php
namespace App\Domain\Models\RBAC\Traits;

use App\Domain\Models\RBAC\Role;
use App\Domain\Models\RBAC\Utilities\ExtractNameUtility;
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

trait RoleAccessManageTrait
{
    /** @var array<string, Role> */
    public array $roles = [];

    public function appendRole(Role $role): self
    {
        if (!in_array($role->name, $this->roles, true)) {
            $this->roles[$role->name] = $role;
        }

        return $this;
    }
    public function forgeRole(
        string $roleName,
        string $description = ''
    ): self {
        if (!in_array($roleName, $this->roles, true)) {
            $role = new Role(
                $roleName,
                $description
            );

            $this->roles[$roleName] = $role;
        }

        return $this;
    }

    /**
     * @return Option<Role>
     */
    public function getRole(Role|string $role): Option
    {
        $nameUtility = ExtractNameUtility::extractName($role);

        return key_exists($nameUtility, $this->roles)
            ? new Some($this->roles[$nameUtility])
            : None::create();
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
        $this->getRole($targetRole)->map(function (Role $role) use ($roles) {
            foreach ($roles as $includer) {
                $ref = ExtractNameUtility::extractName($includer);
                if (!key_exists($ref, $this->roles)) {
                    continue;
                }
                $role->extendRole($this->roles[$ref]);
            }
        });
    }
}