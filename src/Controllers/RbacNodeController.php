<?php

namespace mradang\LaravelRbac\Controllers;

use mradang\LaravelRbac\Services\RbacNodeService;

class RbacNodeController extends Controller
{
    public function all()
    {
        return RbacNodeService::all();
    }
}
