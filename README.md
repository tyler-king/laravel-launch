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
- php artisan launch:install:packages
- php artisan launch:publish:graphql
- php artisan launch:install:cognito
- php artisan launch:install:vapor

need to use WSL2 shell (Ubuntu 20.4 for example)

- `alias sail='bash vendor/bin/sail'`
- `sail up`
- `sail artisan migrate`
