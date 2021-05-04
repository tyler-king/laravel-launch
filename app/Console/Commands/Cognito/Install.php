<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Cognito;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Install extends Command
{
    public $signature = 'launch:install:cognito';

    public $description = 'Cognito';

    private const EOL = "\n";

    public function handle()
    {
        foreach (['.env', '.env.example'] as $env) {
            $currentEnv = File::get(base_path($env));
            $currentEnv = Str::of($currentEnv)->explode(self::EOL);
            //TODO don't override if already exists or at least provide option to use values
            $currentEnv = $currentEnv->reject(function ($line) {
                return Str::of($line)->startsWith([
                    "#COGNITO_REGION=local",
                    "COGNITO_REGION=",
                    "COGNITO_USER_POOL_ID=",
                    "COGNITO_APP_TOKEN=",
                    "COGNITO_LOGIN_URL=",
                ]);
            })->concat([
                "#COGNITO_REGION=local",
                "COGNITO_REGION=",
                "COGNITO_USER_POOL_ID=",
                "COGNITO_APP_TOKEN=",
                "COGNITO_LOGIN_URL=",
            ]);
            File::put(base_path($env), $currentEnv->implode(self::EOL) . self::EOL);
        }
        $this->info('Follow directions in ' . base_path('vendor/tyler-king/serverless-cognito/README.md'));
        //TODO add api.cognito middleware instead of web to vapor-ui.php

        return 0;
    }
}
