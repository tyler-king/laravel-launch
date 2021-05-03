<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
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


/**
 *         "post-update-cmd": [
            "@php artisan vapor-ui:publish --ansi"
        ]
 */
