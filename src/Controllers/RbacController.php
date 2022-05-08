<?php

namespace mradang\LaravelRbac\Controllers;

use Illuminate\Http\Request;
use mradang\LaravelRbac\Services\RbacNodeService;
use mradang\LaravelRbac\Services\RbacRoleService;

class RbacController extends Controller
{
    public function allNodes()
    {
        return RbacNodeService::all();
    }

    public function allRoles()
    {
        return RbacRoleService::all();
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:rbac_role',
        ], [
            'name.unique' => '角色名已存在',
        ]);
        return RbacRoleService::create($request->only('name'));
    }

    public function deleteRole(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1',
        ]);
        return RbacRoleService::delete($request->input('id'));
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|unique:rbac_role,name,' . $request->input('id'),
        ], [
            'name.unique' => '角色名已存在',
        ]);
        return RbacRoleService::update($request->only('id', 'name'));
    }

    public function findRoleWithNodes(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1',
        ]);
        return RbacRoleService::findWithNodes($request->input('id'));
    }

    public function saveRoleSort(Request $request)
    {
        $validatedData = $request->validate([
            '*.id' => 'required|integer',
            '*.sort' => 'required|integer',
        ]);
        RbacRoleService::saveSort($validatedData);
    }

    public function syncRoleNodes(Request $request)
    {
        $validatedData = $request->validate([
            'role_id' => 'required|integer|min:1',
            'nodes' => 'nullable|array',
            'nodes.*' => 'required|integer',
        ]);
        RbacRoleService::syncNodes($validatedData['role_id'], $validatedData['nodes']);
    }
}
