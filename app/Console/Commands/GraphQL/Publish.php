<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\GraphQL;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class Publish extends Command
{
    public $signature = 'launch:publish:graphql';

    public $description = '';

    public function handle()
    {
        Artisan::call('vendor:publish', ["--provider" => "Rebing\GraphQL\GraphQLServiceProvider"]);
        //$this->addPublishToComposer(); //maybe not because it's the config and what if we change it
        return 0;
    }

    private function addPublishToComposer()
    {
        $composer = json_decode(File::get(base_path('composer.json')), true);
        $composer['scripts'] ??= [];
        $composer['scripts']['post-update-cmd'] ??= [];
        $composer['scripts']['post-update-cmd'][] = '@php artisan vendor:publish --provider="Rebing\GraphQL\GraphQLServiceProvider"';
        $composer['scripts']['post-update-cmd'] = array_values(array_unique($composer['scripts']['post-update-cmd']));
        File::put(base_path('composer.json'),  json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
