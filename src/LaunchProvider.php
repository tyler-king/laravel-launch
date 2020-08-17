<?php

namespace TKing;

use Illuminate\Support\ServiceProvider;

class LaunchProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'launch');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/launch'),
        ]);

        $this->publishes([
            __DIR__ . '/config/launch.php' => config_path('launch.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/launch.php',
            'launch'
        );
    }
}
