## CREATE NEW VERSION

- laravel new project-name
- cd project-name
- npm install
- git init
- git add .
- git commit -m Initial Commit
- add repositories to composer.json
  ```
    "repositories": [
  	{
            "type": "git",
            "url": "https://github.com/tyler-king/laravel-launch.git"
        }
  ]
  ```
- composer require tyler-king/launch
- php artisan sail:install
- php artisan sail:publish
- php artisan launch:install:env
- php artisan launch:publish:vscode
- php artisan launch:install:xdebug
- php artisan launch:install:openapi
- php artisan launch:install:packages
- php artisan launch:publish:graphql
- php artisan launch:install:cognito
- php artisan launch:install:vapor

need to use WSL2 shell (Ubuntu 20.4 for example)

- `alias sail='bash vendor/bin/sail'`
- `sail up`
- `sail artisan migrate`

## CDN Cache Control Headers

Configure cache control headers for CDN by defining routes and their corresponding Cache-Control header values in `config/launch.php`.

### Configuration

After publishing the config file, add cache control rules:

```php
"cache_control" => [
    "/api/*" => "public, max-age=3600",
    "/static/*" => "public, max-age=86400, immutable",
    "/docs" => "public, max-age=7200",
]
```

### Usage

The middleware is automatically registered as `cache.control`. Apply it to your routes:

```php
// Apply to all routes in a group
Route::middleware(['cache.control'])->group(function () {
    Route::get('/api/users', [UserController::class, 'index']);
});

// Or apply to specific routes
Route::get('/api/posts', [PostController::class, 'index'])
    ->middleware('cache.control');
```

The middleware will match the request path against configured patterns and set the appropriate Cache-Control header. Patterns support wildcards (`*`) for flexible matching.
