<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\VSCode;

use Illuminate\Console\Command;
use TKing\Launch\Helpers\VSCode;

class Publish extends Command
{
    public $signature = 'launch:publish:vscode';

    public $description = 'Publish VSCode assets for project';

    public function handle(VSCode $vscode)
    {
        $vscode->publishExtensions();
        $vscode->publishXdebug();
        return 0;
    }
}
