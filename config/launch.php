<?php

return [
    "openapi" => base_path("openapi.json"),
    
    /*
    |--------------------------------------------------------------------------
    | CDN Cache Control Headers
    |--------------------------------------------------------------------------
    |
    | Configure cache control headers for CDN. Define specific routes and their
    | corresponding Cache-Control header values. Routes can use wildcards (*).
    |
    | Example:
    | "cache_control" => [
    |     "/api/*" => "public, max-age=3600",
    |     "/static/*" => "public, max-age=86400, immutable",
    | ]
    |
    */
    "cache_control" => [
        // Add your cache control rules here
    ]
];
