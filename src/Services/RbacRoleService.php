<?php

namespace mradang\LaravelRbac\Services;

use mradang\LaravelRbac\Models\RbacRole;

class RbacRoleService
{
    public static function all()
    {
        return RbacRole::orderBy('sort')->get();
    }

    public static function allWithUsers()
    {
        return RbacRole::with('users')->orderBy('sort')->get();
    }

    public static function findWithNodes($id)
    {
        return RbacRole::with('nodes')->findOrFail($id);
    }

    public static function readByIds(array $roles)
    {
        return RbacRole::whereIn('id', $roles)->orderBy('sort')->get();
    }

    public static function create($data)
    {
        $role = new RbacRole($data);
        $role->pinyin = pinyin_abbr($data['name']);
        $role->sort = RbacRole::max('sort') + 1;
        $role->save();
        return $role;
    }

    public static function update($data)
    {
        $role = RbacRole::findOrFail($data['id']);
        $role->fill($data);
        $role->save();
        return $role;
    }

    public static function syncNodes($id, array $nodes)
    {
        $role = RbacRole::findOrFail($id);
        $role->nodes()->sync($nodes);
        $users = $role->users;
        foreach ($users as $user) {
            $user->rbacResetSecret();
        }
    }

    public static function delete($id)
    {
        $role = RbacRole::find($id);
        $users = $role->users;
        foreach ($users as $user) {
            $user->rbacResetSecret();
        }
        $role->users()->detach();
        $role->nodes()->detach();
        $role->delete();
    }

    // 保存排序值，data中的项目需要2个属性：id, sort
    public static function saveSort(array $data)
    {
        foreach ($data as $item) {
            RbacRole::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }
    }
}
