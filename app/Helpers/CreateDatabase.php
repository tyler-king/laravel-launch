<?php

declare(strict_types=1);

namespace TKing\Launch\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class CreateDatabase
{

    public static function create(bool $force): string
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        if ($driver !== 'mysql') {
            throw new RuntimeException("Only works with mysql for now. This is $driver");
        }
        $database = DB::getDatabaseName();
        $DBExists = DB::statement('SELECT `SCHEMA_NAME` FROM INFORAMTION_SCHEMA.SCHEMATA WHERE `SCHEMA_NAME` = :name', ['name' => $database]);
        $DBExists = count($DBExists) > 0;
        if (!$DBExists) {
            if ($force) {
                Schema::createDatabase($database);
                return "Created database $database";
            } else {
                return "Database $database does not exist";
            }
        } else {
            return "Database $database already exists";
        }
    }
}
