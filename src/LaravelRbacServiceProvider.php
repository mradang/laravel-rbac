<?php

namespace mradang\LaravelRbac;

use Illuminate\Support\ServiceProvider;
use mradang\LaravelRbac\Console\MakeRouteDescFileCommand;
use mradang\LaravelRbac\Console\RefreshRbacNodeCommand;
use mradang\LaravelRbac\Middleware\RbacAbilitiesMiddleware;

class LaravelRbacServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerRouteMiddleware();
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

    protected function registerRouteMiddleware()
    {
        $this->app['router']->aliasMiddleware('rbac', RbacAbilitiesMiddleware::class);
    }
}
