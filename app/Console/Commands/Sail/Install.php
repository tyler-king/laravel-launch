<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Sail;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    public $signature = 'launch:install:sail';

    public $description = '';

    public function handle()
    {
        //TODO doesn't work because install requires interaction
        Artisan::call('sail:install');
        Artisan::call('sail:publish');
        return 0;
    }
}
