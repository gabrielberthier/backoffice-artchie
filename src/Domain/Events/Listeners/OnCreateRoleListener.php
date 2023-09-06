<?php

namespace App\Listener;

use App\Domain\Events\Events\OnRoleCreateEvent;
use App\Domain\Events\ListenerInterface;
use App\Domain\Models\RBAC\Role;
use Psr\Log\LoggerInterface;



interface RoleDataStorerInterface
{
    public function storeRole(Role $role): void;
}

/**
 * @implements ListenerInterface<OnRoleCreateEvent>
 */
class OnCreateRoleListener implements ListenerInterface
{
    public function __construct(
        public readonly LoggerInterface $logger,
        public readonly RoleDataStorerInterface $roleStorage
    ) {
    }

    public function execute(OnRoleCreateEvent $subject): void
    {
        $role = $subject->role;

        $this->roleStorage->storeRole($role);
    }
}