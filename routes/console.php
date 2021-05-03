<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use TKing\App\Helpers\VSCode;
use TKing\Launch\Helpers\OpenApi;
use TKing\Launch\Helpers\CreateDatabase;
use TKing\Launch\Helpers\Vapor;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('launch:openapi:dump', function () {
    $openapi = OpenApi::fromConfig(config("launch.openapi"))->get();
    if (empty($openapi)) {
        $this->error("No OpenAPI config defined");
        return;
    }
    $this->info(json_encode($openapi, JSON_PRETTY_PRINT));
})->purpose('Output OpenAPI config');

Artisan::command('launch:db:create {--force}', function () {
    try {
        $this->info(CreateDatabase::create($this->option('force')));
    } catch (Exception $e) {
        $this->error($e->getMessage());
    }
})->purpose('Output OpenAPI config');

Artisan::command('launch:init:vapor {--id=} {--name=} {--domain=}', function () {
    $id = $this->option('id') ?? '';
    $name = $this->option('name') ?? '';
    $domain = $this->option('domain') ?? '';

    $vapor = new Vapor($id, $name, $domain);

    $vapor->copyGithubActionsCi();
    $vapor->copyVaporYml();
})->purpose('Adds template vapor yml and github actions for deploying CI');

Artisan::command('launch:setup:vscode', function () {
    $vscode = new VSCode();
    $vscode->publishExtensions();
    $vscode->publishXdebug();
})->purpose('Set up VSCode for project');

Artisan::command('launch:install:sail', function () {
    Artisan::call('sail:install');
    Artisan::call('sail:publish');
})->purpose('Set up Sail');

Artisan::command('launch:install:env {--projectNo=}', function () {
    $projectNumber = $this->option('projectNo');
    if (!isset($projectNumber)) {
        // .env.example copy if not exists. Add forwarding port information with increments
    }
})->purpose('Set up Env');

Artisan::command('launch:install', function () {
    Artisan::call('launch:install:sail');
    Artisan::call('launch:install:env');
    Artisan::call('launch:install:vapor');
});

/**
 *         "post-update-cmd": [
            "@php artisan vapor-ui:publish --ansi"
        ]
 */
