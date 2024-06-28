<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dbcreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dbcreate {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new PostgreSQL database based on the database config file or the provided name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $schemaName = $this->argument('name') ?: config("database.connections.pgsql.database");
        $encoding = 'UTF8'; // Example encoding
        $collation = 'en_US.UTF-8'; // Example collation
        $ctype = 'en_US.UTF-8'; // Example character classification

        // Temporarily set the database connection to null
        config(["database.connections.pgsql.database" => null]);

        // Reconnect with the new configuration
        DB::purge('pgsql');
        DB::reconnect('pgsql');

        // Check if the database exists
        $databaseExists = DB::select("SELECT 1 FROM pg_database WHERE datname = ?", [$schemaName]);

        if (empty($databaseExists)) {
            $query = "CREATE DATABASE $schemaName WITH ENCODING '$encoding' LC_COLLATE '$collation' LC_CTYPE '$ctype' TEMPLATE template0;";
            DB::statement($query);
        }

        // Restore the database connection configuration to the newly created or existing database
        config(["database.connections.pgsql.database" => $schemaName]);

        // Reconnect to the newly created or existing database
        DB::purge('pgsql');
        DB::reconnect('pgsql');
    }
}
