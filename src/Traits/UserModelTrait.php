<?php

namespace mradang\LaravelRbac\Traits;

use mradang\LaravelRbac\Models\RbacRole;
use mradang\LaravelRbac\Services\RbacNodeService;

trait UserModelTrait
{
    abstract protected function getIsAdminAttribute();

    public function rbacRoles()
    {
        return $this->belongsToMany(RbacRole::class, 'rbac_role_user', 'user_id', 'role_id')->orderBy('sort');
    }

    public function getAccessAttribute()
    {
        $nodes = RbacNodeService::publicNodes();

        if ($this->isAdmin) {
            $nodes = $nodes->merge(RbacNodeService::AuthNodes());
        } else {
            foreach ($this->rbacRoles as $role) {
                $nodes = $nodes->merge($role->nodes->pluck('name'));
            }
        }

        return $nodes->unique()->toArray();
    }

    public function rbacSyncRoles(array $roles)
    {
        $this->load('rbacRoles');
        $old = $this->rbacRoles->pluck('name')->toArray();
        $this->rbacRoles()->sync($roles);
        $new = $this->refresh()->rbacRoles->pluck('name')->toArray();
        $remove = array_diff($old, $new);
        $add = array_diff($new, $old);
        if ($old || $new) {
            info(sprintf(
                '修改用户「%s」角色%s%s',
                $this->name,
                $remove ? sprintf('，移除角色「%s」', implode(',', $remove)) : '',
                $add ? sprintf('，新增角色「%s」', implode(',', $add)) : ''
            ));
        }
    }

    public function rbacDeleteUser()
    {
        $this->rbacRoles()->detach();
    }
}
