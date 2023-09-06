<?php
namespace App\Domain\Models\RBAC;

use App\Domain\Models\Account;


class Executor
{
    static function execute()
    {
        $account = new Account(null, 'mail', 'username', 'pass', 'COMMON');
        $accountRole = new RoleProfile($account);
        $role = new Role('image_role', '');
        $resource = new Resource('image', 'images resources');
        $canCreate = new Permission('can:create', ContextIntent::READ);
        $role->addPermissionToResource($canCreate, $resource);
        $accountRole->addRole($role);

        foreach ($accountRole->roles as $role) {
            if ($role->canAcess($resource, ContextIntent::CREATE)) {
                return true;
            }
        }

        return false;
    }
}