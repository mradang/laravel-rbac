<?php

namespace mradang\LaravelRbac\Console;

use Illuminate\Console\Command;
use mradang\LaravelRbac\Services\RbacNodeService;

class MakeRouteDescFileCommand extends Command
{
    protected $signature = 'rbac:MakeRouteDescFile';

    protected $description = 'Generate route description file';

    public function handle()
    {
        $n = RbacNodeService::makeRouteDescFile();
        if ($n) {
            $this->info('成功生成路由描述文件');
        } else {
            $this->error('生成路由描述文件失败');
        }
    }
}
