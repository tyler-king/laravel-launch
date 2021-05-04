<?php

declare(strict_types=1);

namespace TKing\Launch\Console\Commands\Database;

use Illuminate\Console\Command;
use TKing\Launch\Helpers\CreateDatabase;

class Create extends Command
{
    public $signature = 'launch:db:create {--force}';

    public $description = 'Create Database if it does not exist';

    public function handle(CreateDatabase $createDatabase)
    {
        try {
            $this->info($createDatabase->create($this->option('force')));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        return 0;
    }
}
