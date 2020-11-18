<?php

namespace mradang\LaravelRbac\Services;

use mradang\LaravelRbac\Models\RbacAccess;

class RbacAccessService
{
    public static function clearInvalidAccess()
    {
        $ids = RbacNodeService::ids();
        RbacAccess::whereNotIn('node_id', $ids)->delete();
    }
}
