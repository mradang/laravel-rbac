<?php

namespace Tests;

use Illuminate\Support\Arr;
use mradang\LaravelRbac\Models\RbacNode;
use mradang\LaravelRbac\Services\RbacNodeService;
use mradang\LaravelRbac\Services\RbacRoleService;

class FeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @covers RbacNodeService::refresh
     * @covers RbacNodeService::all
     * @covers RbacRoleService::create
     */
    public function testBasicFeatures()
    {
        $user = User::create(['name' => '张三']);
        $this->assertSame(1, $user->id);

        RbacNodeService::refresh();
        $this->assertDatabaseHas('rbac_node', [
            'name' => '/rbac/createRole',
            'description' => '新建角色',
        ]);
        $node = RbacNode::where('name', '/rbac/createRole')->first();

        $role = RbacRoleService::create([
            'name' => '管理员',
        ]);
        $this->assertDatabaseHas('rbac_role', [
            'name' => '管理员',
            'pinyin' => 'gly',
            'sort' => 1,
        ]);

        RbacRoleService::syncNodes($role->id, RbacNodeService::ids()->toArray());
        $this->assertDatabaseHas('rbac_access', [
            'role_id' => $role->id,
            'node_id' => $node->id,
        ]);

        $user->rbacSyncRoles([$role->id]);
        $this->assertTrue(in_array($node->name, $user->access));
    }
}
