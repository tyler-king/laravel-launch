<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\XDebug;

use Illuminate\Console\Command;
use TKing\Launch\Helpers\XDebug;

class Publish extends Command
{
    public $signature = 'launch:publish:xdebug';

    public $description = 'Publish XDebug assets for project';

    public function handle(XDebug $debug)
    {
        $debug->publishIni();
        return 0;
    }
}
