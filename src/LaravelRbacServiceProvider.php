<?php

namespace mradang\LaravelRbac;

use Illuminate\Support\ServiceProvider;
use mradang\LaravelRbac\Console\MakeRouteDescFileCommand;
use mradang\LaravelRbac\Console\RefreshRbacNodeCommand;

class LaravelRbacServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // 配置文件
            $this->publishes([
                \dirname(__DIR__) . '/config/rbac.php' => config_path('rbac.php'),
            ], 'config');
        }

        $this->registerRoutes();
        $this->registerCommands();
        $this->registerMigrations();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            \dirname(__DIR__) . '/config/rbac.php',
            'rbac'
        );
    }

    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRouteDescFileCommand::class,
                RefreshRbacNodeCommand::class,
            ]);
        }
    }

    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(\dirname(__DIR__) . '/migrations/');
        }
    }
}
