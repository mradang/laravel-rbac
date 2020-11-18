<?php

namespace mradang\LaravelRbac\Traits;

use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use mradang\LaravelRbac\Services\RbacNodeService;

trait UserModelTrait
{
    public function rbacRoles()
    {
        return $this->belongsToMany('mradang\LaravelRbac\Models\RbacRole', 'rbac_role_user', 'user_id', 'role_id');
    }

    abstract protected function getIsAdminAttribute();

    public function getAccessAttribute()
    {
        $nodes = RbacNodeService::publicNodes();

        if ($this->isAdmin) {
            $nodes = array_merge($nodes, RbacNodeService::AuthNodes());
        } else {
            foreach ($this->rbacRoles as $role) {
                $nodes = array_merge($nodes, $role->nodes->pluck('name')->toArray());
            }
        }

        return array_values(array_unique($nodes));
    }

    public function rbacResetSecret()
    {
        $this->secret = Str::random(8);
        return $this->save();
    }

    public function rbacMakeToken(array $fields = ['id', 'name'])
    {
        if (empty($this->secret)) {
            $this->rbacResetSecret();
        }
        $payload = Arr::only($this->toArray(), $fields);
        $payload['exp'] = time() + config('rbac.ttl');
        return JWT::encode($payload, $this->secret);
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
            $this->rbacResetSecret();
            Log::info(sprintf(
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
        $this->rbacResetSecret();
    }
}
