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
                
                // Auto-wrap GET requests with cache.control middleware
                if (strtolower($type) === 'get') {
                    $request->middleware('cache.control');
                }
                
                if (isset($configuration['auth'])) {
                    //TODO alert if using /{} because it will intercept a lot of requests
                    $request->middleware($configuration['auth']);
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
    die($e->getMessage());
    throw new \RuntimeException("Invalid OpenAPI configuration");
}
