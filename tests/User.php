<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use mradang\LaravelRbac\Traits\UserModelTrait;

class User extends Model
{
    use UserModelTrait;

    protected $fillable = ['name'];

    public function getIsAdminAttribute()
    {
        return false;
    }
}
