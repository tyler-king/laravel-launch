<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Vapor;

use Illuminate\Console\Command;
use TKing\Launch\Helpers\Vapor;

class Setup extends Command
{
    public $signature = 'launch:setup:vapor {--id=} {--name=} {--domain=}';

    public $description = 'Adds template vapor yml and github actions for deploying CI';

    public function handle()
    {
        $id = $this->option('id') ?? '';
        $name = $this->option('name') ?? '';
        $domain = $this->option('domain') ?? '';

        $vapor = new Vapor($id, $name, $domain);

        $vapor->copyGithubActionsCi();
        $vapor->copyVaporYml();
        return 0;
    }
}
