<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\OpenAPI;

use Illuminate\Console\Command;

class Install extends Command
{
    public $signature = 'launch:install:openapi';

    public $description = 'Install OpenAPI routes';

    public function handle()
    {
        //NOW
        //replace ExampleTest with 404 error
        //replace RouterMiddleWare with no prefixes
        //replace web.php with nothing
        //replace api.php with nothing
        return 0;
    }
}
