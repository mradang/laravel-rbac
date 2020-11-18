<?php

namespace mradang\LaravelRbac;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Auth;

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
        $this->registerGuard();
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
                Console\MakeRouteDescFileCommand::class,
                Console\RefreshRbacNodeCommand::class,
            ]);
        }
    }

    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(\dirname(__DIR__) . '/migrations/');
        }
    }

    protected function registerGuard()
    {
        Auth::viaRequest('rbac-token', function ($request) {
            $user = Services\AuthService::checkToken($request);
            return $user ?: null;
        });
    }

    protected function registerRouteMiddleware()
    {
        // 认证中间件
        $this->app['router']->aliasMiddleware('auth.basic', Middleware\Authenticate::class);
        $this->app['router']->aliasMiddleware('auth', Middleware\Authorization::class);

        // 修改全局认证配置
        config([
            'auth.defaults.guard' => 'api',
            'auth.guards.api.driver' => 'rbac-token',
        ]);
    }
}
