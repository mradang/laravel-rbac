<?php

namespace mradang\LaravelRbac\Console;

use Illuminate\Console\Command;
use mradang\LaravelRbac\Services\RbacRoleService;

class AuthorizeAdminRoleCommand extends Command
{
    protected $signature = 'rbac:AuthorizeAdminRole {admin_role_name}';

    protected $description = 'Authorize all permissions of administrator role';

    public function handle()
    {
        RbacRoleService::authorizeAdminRole($this->argument('admin_role_name'));
    }
}
