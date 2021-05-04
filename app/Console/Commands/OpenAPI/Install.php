<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\OpenAPI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Install extends Command
{
    public $signature = 'launch:install:openapi';

    public $description = 'Install OpenAPI routes';

    public function handle()
    {
        if (File::exists(base_path('tests/Feature/ExampleTest.php'))) {
            File::put(
                base_path('tests/Feature/ExampleTest.php'),
                Str::of(File::get(base_path('tests/Feature/ExampleTest.php')))->replace(200, 404)
            );
        }

        if (File::exists(base_path('routes/api.php'))) {
            File::put(
                base_path('routes/api.php'),
                '<?php' . "\n"
            );
        }

        if (File::exists(base_path('routes/web.php'))) {
            File::put(
                base_path('routes/web.php'),
                '<?php' . "\n"
            );
        }

        //TODO make automated
        $this->info('Manually replace RouterMiddleWare with no prefixes');
        return 0;
    }
}
