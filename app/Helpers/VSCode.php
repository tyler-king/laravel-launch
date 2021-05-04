<?php

declare(strict_types=1);

namespace TKing\Launch\Helpers;

use Illuminate\Support\Facades\File;

class VSCode
{

    private const RECOMMENDATIONS = [
        "bmewburn.vscode-intelephense-client",
        "felixfbecker.php-debug",
        "neilbrayfield.php-docblocker",
        "donjayamanne.githistory",
        "github.vscode-pull-request-github",
        "eamodio.gitlens",
        "esbenp.prettier-vscode",
        "2gua.rainbow-brackets",
        "alexcvzz.vscode-sqlite",
        "gruntfuggly.todo-tree"
    ];


    public function publishExtensions()
    {
        $path = base_path('.vscode' . DIRECTORY_SEPARATOR . 'extensions.json');
        $extensions = [];

        if (!File::exists(".vscode")) {
            File::makeDirectory(".vscode");
        }

        if (File::exists($path)) {
            $extensions = json_decode(File::get($path), true) ?? [];
        }
        $extensions['recommendations'] = array_merge(
            $decode['recommendations'] ?? [],
            self::RECOMMENDATIONS
        );
        $extensions['recommendations'] = array_values(array_unique($extensions['recommendations']));
        File::put($path, json_encode($extensions, JSON_PRETTY_PRINT));
    }

    public function publishXdebug()
    {
        $path = base_path('.vscode' . DIRECTORY_SEPARATOR . 'launch.json');
        File::copy($this->thisDirectory('launch.json'), $path);
    }

    private function thisDirectory(string $path = ''): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . class_basename($this) . DIRECTORY_SEPARATOR . $path;
    }
}
