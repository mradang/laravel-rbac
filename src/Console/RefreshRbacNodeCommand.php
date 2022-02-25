<?php

namespace mradang\LaravelRbac\Console;

use Illuminate\Console\Command;
use mradang\LaravelRbac\Services\RbacNodeService;
use Illuminate\Support\Facades\DB;

class RefreshRbacNodeCommand extends Command
{
    protected $signature = 'rbac:RefreshRbacNode';

    protected $description = 'Refresh the routing node and read the comment file';

    public function handle()
    {
        RbacNodeService::refresh();
    }
}
