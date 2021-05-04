<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\XDebug;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use TKing\Launch\Helpers\XDebug;

class Install extends Command
{
    public $signature = 'launch:install:xdebug';

    public $description = 'Set up Xdebug';

    public function handle(Xdebug $debug)
    {
        $debug->writeToDockerInstall();
        Artisan::call('launch:publish:xdebug');
        return 0;
    }
}
