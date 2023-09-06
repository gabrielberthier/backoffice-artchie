<?php

declare(strict_types=1);

namespace Tests\Domain\Rbac;

use App\Domain\Models\Account;
use App\Domain\Models\RBAC\AccessControl;
use App\Domain\Models\RBAC\ContextIntent;
use App\Domain\Models\RBAC\Permission;
use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Role;
use App\Domain\Models\RBAC\RoleProfile;
use PHPUnit\Framework\TestCase;


class ExecutorTest extends TestCase
{
    private Account $account;
    private RoleProfile $profile;

    protected function setUp(): void
    {
        $this->account = new Account(null, 'mail', 'username', 'pass', 'COMMON');
        $this->profile = new RoleProfile($this->account);
    }

    public function testShouldReturnTrueForAccessableResource()
    {
        $role = new Role('image_role', '');
        $resource = new Resource('image', 'images resources');
        $canCreate = new Permission('can:create', ContextIntent::READ);
        $role->addPermissionToResource($canCreate, $resource);
        $this->profile->addRole($role);

        $this->assertTrue($this->profile->canAccess($resource, ContextIntent::READ));
    }

    public function testShouldReturnFalseForInaccessableResource()
    {
        $role = new Role('image_role', '');
        $resource = new Resource('image', 'images resources');
        $canCreate = new Permission('can:create', ContextIntent::READ);
        $role->addPermissionToResource($canCreate, $resource);
        $this->profile->addRole($role);

        $this->assertFalse($this->profile->canAccess($resource, ContextIntent::CREATE));
    }

    public function testAccessControlEmitsStringObject()
    {
        $accessControl = new AccessControl();
        $resource = $accessControl->createResource('image', 'images resources');
        $role = $accessControl
            ->forgeRole('image:role')
            ->addPermissionToRole('image:role', $resource, ContextIntent::CREATE)
            ->getRole("image:role")->get();

        $this->assertStringContainsStringIgnoringCase(
            json_encode($role->jsonSerialize()),
            $accessControl->constructObject()
        );
    }

    public function testAccessControlWillAllowPass()
    {
        $accessControl = new AccessControl();
        $resource = $accessControl->createResource('image', 'images resources');
        $accessControl
            ->forgeRole('image:role')
            ->addPermissionToRole(
                'image:role',
                $resource,
                ContextIntent::CREATE
            );

        $this->assertTrue(
            $accessControl->tryAccess(
                'image:role',
                'image',
                ContextIntent::CREATE
            )
        );
    }

    public function testAccessControlWillNotAllowPassForDifferentIntent()
    {
        $accessControl = new AccessControl();
        $resource = $accessControl->createResource('image', 'images resources');
        $accessControl
            ->forgeRole('image:role')
            ->addPermissionToRole(
                'image:role',
                $resource,
                ContextIntent::READ
            );

        $this->assertFalse(
            $accessControl->tryAccess(
                'image:role',
                'image',
                ContextIntent::CREATE
            )
        );
    }

    public function testAccessControlWillAllowPassForDifferentIntentButTruethyFallback()
    {
        $accessControl = new AccessControl();
        $resource = $accessControl->createResource('image', 'images resources');
        $accessControl
            ->forgeRole('image:role')
            ->addPermissionToRole(
                'image:role',
                $resource,
                ContextIntent::READ
            );

        $this->assertTrue(
            $accessControl->tryAccess(
                'image:role',
                'image',
                ContextIntent::CREATE,
                static function ($role, $resource, $permission) {
                    return true;
                }
            )
        );
    }

    public function testAccessControlWillNotAllowPassForDifferentIntentAndFalsyFallback()
    {
        $accessControl = new AccessControl();
        $resource = $accessControl->createResource('image', 'images resources');
        $accessControl
            ->forgeRole('image:role')
            ->addPermissionToRole(
                'image:role',
                $resource,
                ContextIntent::READ
            );

        $this->assertFalse(
            $accessControl->tryAccess(
                'image:role',
                'image',
                ContextIntent::CREATE,
                static function ($role, $resource, $permission) {
                    return false;
                }
            )
        );
    }

}