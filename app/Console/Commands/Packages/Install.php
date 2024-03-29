<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Packages;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class Install extends Command
{
    public $signature = 'launch:install:packages';

    public $description = '';

    public function handle()
    {

        $composer = json_decode(File::get(base_path('composer.json')), true);

        $list = [
            'serverless-cognito' => ['name' => 'tyler-king/serverless-cognito', 'repositories' => [
                [
                    "type" => "git",
                    "url" => "https://github.com/tyler-king/php-jwt.git"
                ],
                [
                    "type" => "git",
                    "url" => "https://github.com/tyler-king/laravel-serverless-cognito.git"
                ]
            ]],
            'graphql-laravel' => ['name' => 'rebing/graphql-laravel'],
            'vapor' => ['name' => 'laravel/vapor-cli laravel/vapor-core laravel/vapor-ui']
        ];
        $list = array_map(function ($value) use ($composer) {
            $names = explode(' ', $value['name']);
            $names = array_filter($names, function ($name) use ($composer) {
                return !isset($composer['require'][$name]);
            });
            $value['name'] = trim(implode(' ', $names));
            return $value;
        }, $list);
        $list = array_filter($list, function ($value) {
            return !empty($value['name']);
        });

        if (count($list) == 0) {
            $this->info('Everything is already installed');
            return;
        }

        $name = $this->choice(
            "Do you want to install any of the packages",
            array_keys($list),
            $defaultIndex = null,
            $maxAttempts = null,
            $allowMultipleSelections = true
        );
        $all = [];
        foreach ($name as $package) {
            $all[] = $list[$package]['name'];
            $repositories = array_merge($repositories ?? [], $list[$package]['repositories'] ?? []);
        }
        if (!empty($all)) {
            $this->mergeRepositories($repositories);
            $all = implode(' ', $all);
            exec("composer require $all");
        }
        return 0;
    }

    private function mergeRepositories($repositories)
    {
        if (count($repositories) > 0) {
            $composer = json_decode(File::get(base_path('composer.json')), true);
            $composer['repositories'] ??= [];
            $composer['repositories'] = array_merge(
                $composer['repositories'],
                $repositories
            );
            $composer['repositories'] = array_values(array_map("unserialize", array_unique(array_map("serialize",  $composer['repositories']))));
            File::put(base_path('composer.json'),  json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
}
