<?php

namespace TKing\Launch\Providers;

use Illuminate\Support\ServiceProvider;
use TKing\Launch\Console\Commands;
use TKing\Launch\Http\Middleware\SetCacheControlHeaders;
use TKing\Launch\Http\Middleware\ThrottleRequests;

class LaunchProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom($this->basePath('routes/web.php'));
        $this->loadRoutesFrom($this->basePath('routes/api.php'));

        // Register middleware alias
        $router = $this->app['router'];
        $router->aliasMiddleware('cache.control', SetCacheControlHeaders::class);
        $router->aliasMiddleware('throttle', ThrottleRequests::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Install::class,
                Commands\Cognito\Install::class,
                Commands\OpenAPI\Dump::class,
                Commands\OpenAPI\Install::class,
                Commands\Database\Create::class,
                Commands\Env\Install::class,
                Commands\GraphQL\Publish::class,
                Commands\Packages\Install::class,
                Commands\Sail\Install::class,
                Commands\Vapor\Setup::class,
                Commands\Vapor\Install::class,
                Commands\VSCode\Publish::class,
                Commands\XDebug\Install::class,
                Commands\XDebug\Publish::class
            ]);
        }

        $this->loadViewsFrom($this->basePath('resources/views'), 'launch');

        $this->publishes([
            $this->basePath('resources/views') => resource_path('views/vendor/launch'),
        ]);

        $this->publishes([
            $this->basePath('config/launch.php') => config_path('launch.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            $this->basePath('config/launch.php'),
            'launch'
        );
    }

    private function basePath(string $path)
    {
        return __DIR__ . '/../../' . $path;
    }
}
