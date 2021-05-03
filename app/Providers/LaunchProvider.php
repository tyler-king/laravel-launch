<?php

namespace TKing\Launch\Providers;

use Illuminate\Support\ServiceProvider;

class LaunchProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom($this->basePath('routes/web.php'));
        $this->loadRoutesFrom($this->basePath('routes/api.php'));
        $this->loadRoutesFrom($this->basePath('routes/console.php'));

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
