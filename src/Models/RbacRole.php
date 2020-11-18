<?php

namespace mradang\LaravelRbac\Models;

use Illuminate\Database\Eloquent\Model;

class RbacRole extends Model
{
    protected $table = 'rbac_role';

    protected $fillable = ['name'];

    protected $hidden = ['sort'];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(config('rbac.user_model'), 'rbac_role_user', 'role_id', 'user_id');
    }

    public function nodes()
    {
        return $this->belongsToMany('mradang\LaravelRbac\Models\RbacNode', 'rbac_access', 'role_id', 'node_id');
    }
}
