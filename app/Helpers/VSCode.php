<?php

declare(strict_types=1);

namespace TKing\App\Helpers;

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
        $extensions = [];
        if (File::exists(base_path('.vscode/extensions.json'))) {
            $extensions = json_decode(File::get(base_path('.vscode/extensions.json')), true) ?? [];
        }
        $extensions['recommendations'] = array_merge(
            $decode['recommendations'] ?? [],
            self::RECOMMENDATIONS
        );
        $extensions['recommendations'] = array_values(array_unique($extensions['recommendations']));
        File::put(base_path('.vscode/extensions.json'), json_encode($extensions, JSON_PRETTY_PRINT));
    }

    public function publishXdebug()
    {
        File::copy($this->thisDirectory('launch.json'), base_path('.vscode/launch.json'));
    }

    private function thisDirectory(string $path = ''): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . class_basename($this) . DIRECTORY_SEPARATOR . $path;
    }
}
