<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use TKing\Launch\Helpers\OpenApi;

try {
    $openapi = OpenApi::fromConfig(config("launch.openapi"));
    foreach ($openapi->getPaths() as $route => $method) {
        foreach ($method as $type => $configuration) {
            if (isset($configuration['controller'])) {
                $request = Route::{$type}($route, "App\\Http\\Controllers\\" . $configuration['controller']);
                
                // Apply cache.control middleware if x-cache-control is defined for this endpoint
                if (isset($configuration['x-cache-control'])) {
                    $request = $request->middleware('cache.control:' . $configuration['x-cache-control']);
                }

                // Apply throttle middleware if x-throttle is defined for this endpoint
                if (isset($configuration['x-throttle'])) {
                    $request = $request->middleware('throttle:' . $configuration['x-throttle']);
                }

                if (isset($configuration['auth'])) {
                    $request = $request->middleware($configuration['auth']);
                }
            }
        }
    }
    if (!empty($openapi->getHomePage())) {
        Route::get("/", function () use ($openapi) {
            return redirect($openapi->getHomePage());
        });
    }
} catch (\Exception $e) {
    throw new \RuntimeException("Invalid OpenAPI configuration");
}
