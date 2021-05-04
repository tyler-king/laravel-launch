<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Env;

use Illuminate\Console\Command;
use TKing\Launch\Helpers\Env;

class Install extends Command
{
    public $signature = 'launch:install:env';

    public $description = 'Set up Env';

    public function handle(Env $env)
    {
        $name = $env->getName();
        $projectNumber = $env->getProject();
        $database = $env->getDatabase();

        $name = $this->ask('What is the name of your project?', $name);
        $projectNumber = $this->ask('What project number is this? (This will be used to increment Forward Ports so you can run multiple)', $projectNumber);
        $database = $this->ask('What is your initial database name?', $database);
        $env->edit($name, $database, $projectNumber);
        $env->fixup();
        return 0;
    }
}
