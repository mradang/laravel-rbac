<?php

namespace mradang\LaravelRbac\Models;

use Illuminate\Database\Eloquent\Model;

class RbacAccess extends Model
{
    protected $table = 'rbac_access';

    public $timestamps = false;
}
