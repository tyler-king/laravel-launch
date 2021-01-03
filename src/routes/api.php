<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

if (file_exists(config("launch.openapi"))) {
    $openapi = json_decode(file_get_contents(config("launch.openapi")), true);

    foreach ($openapi['paths'] as $route => $method) {
        foreach ($method as $type => $configuration) {
            if (isset($configuration['controller'])) {
                $request = Route::{$type}($route, "App\\Http\\Controllers\\" . $configuration['controller']);
                if (isset($configuration['auth'])) {
                    //NOW alert if using /{} because it will intercept a lot of requests
                    $request->middleware($configuration['auth']);
                }
            }
        }
    }
    if ($openapi['x-homepage']) {
        Route::get("/", function () use ($openapi) {
            return redirect($openapi['x-homepage']);
        });
    }
}
