<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Vapor;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    public $signature = 'launch:install:vapor';

    public $description = '';

    public function handle()
    {
        Artisan::call('vapor-ui:install');
        Artisan::call('vendor:publish', ['--tag' => 'vapor-ui-config']);
        Artisan::call('vapor-ui:publish');
        $this->addPublishToComposer();

        /*
        `sail shell`
        - `./vendor/bin/vapor login`
        - `./vendor/bin/vapor init -n` //or check for current just this sail artisan project:setup:vapor --name= --domain=
        - `./vendor/bin/vapor env:pull production`
        - make edits
        - `./vendor/bin/vapor env:push production`  (Press enter again to remove file)
        - `exit`*/

        $this->info('Edit VaporUiServiceProvider if you want to it viewable');
        return 0;
    }

    private function addPublishToComposer()
    {
        $composer = json_decode(File::get(base_path('composer.json')), true);
        $composer['scripts'] ??= [];
        $composer['scripts']['post-update-cmd'] ??= [];
        $composer['scripts']['post-update-cmd'][] = '@php artisan vapor-ui:publish --ansi';
        $composer['scripts']['post-update-cmd'] = array_values(array_unique($composer['scripts']['post-update-cmd']));
        File::put(base_path('composer.json'),  json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

/*

## VAPOR FAQ

- had already added domain in console
- connecting to rds 
  - needed to be in us-west-2 for correct vpc 
  - had to move rds to public vapor subnet and security groups
*/