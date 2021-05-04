<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\OpenAPI;

use Illuminate\Console\Command;
use TKing\Launch\Helpers\OpenApi;

class Dump extends Command
{
    public $signature = 'launch:openapi:dump';

    public $description = 'Output OpenAPI config';

    public function handle()
    {
        $openapi = OpenApi::fromConfig(config("launch.openapi"))->get();
        if (empty($openapi)) {
            $this->error("No OpenAPI config defined");
            return;
        }
        $this->info(json_encode($openapi, JSON_PRETTY_PRINT));
        return 0;
    }
}
