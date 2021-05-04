<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    public $signature = 'launch:install';

    public $description = '';

    public function handle()
    {
        //TODO would like to figure out how to call these sequentially with interaction
        Artisan::call('launch:install:env');
        Artisan::call('launch:install:sail');
        return 0;
    }
}
