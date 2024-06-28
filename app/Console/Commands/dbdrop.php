<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dbdrop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dbdrop {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $schemaName = $this->argument('name') ?: config("database.connections.pgsql.database");

        config(["database.connections.pgsql.database" => null]);

        DB::purge('pgsql');
        DB::reconnect('pgsql');

        $databaseExists = DB::select("SELECT 1 FROM pg_database WHERE datname = ?", [$schemaName]);

        if (!empty($databaseExists)) {
            $query = "DROP DATABASE $schemaName;";
            DB::statement($query);
            $this->info("Database $schemaName dropped successfully.");
        } else {
            $this->info("Database $schemaName does not exist.");
        }

        // Optionally, restore the previous database connection
        $defaultDatabase = config("database.connections.pgsql.database");
        if ($defaultDatabase) {
            config(["database.connections.pgsql.database" => $defaultDatabase]);
            DB::purge('pgsql');
            DB::reconnect('pgsql');
        }
    }
}
