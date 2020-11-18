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
        $ready = false;
        try {
            $ready = !empty(DB::select('DESCRIBE rbac_node'));
        } catch (\Exception $e) {
            $ready = false;
        }

        if ($ready) {
            RbacNodeService::refresh();
        } else {
            info('数据库表不存在，未能刷新 RBAC 节点。');
        }
    }
}
