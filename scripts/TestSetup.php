<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class TestSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will setup the test environment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Define the setup steps
        $steps = [
            'Checking if test database exists...',
            'Creating test database...',
            'Running migrations for testing connection...',
            'Installing Passport without migrations...',
            'Creating test Passport client...',
        ];

        // Initialize Progress Bar with the number of steps
        $progressBar = $this->output->createProgressBar(count($steps));
        $progressBar->start();

        try {

            // Step 1: Check if the test database exists
            if (!$this->checkIfDatabaseExists('usermanagement-test')) {
                $this->info("\nCreating test database...");
                $this->call('db:create', ['name' => 'usermanagement-test']);
            } else {
                $this->info("\nTest database already exists.");
            }
            $progressBar->advance();


            // Step 2: Run migrations for testing connection
            $this->info("\nRunning migrations for testing connection...");
            //drop all tables in testing database
            DB::connection('testing')->getSchemaBuilder()->dropAllTables();
            $this->call('migrate:fresh', ['--database' => 'testing']);
            $progressBar->advance();

            // Step 3: Install Passport if not installed
            // check laravel passport in vendor
            if (!class_exists('Laravel\Passport\Passport')) {
                $this->info("\nInstalling Passport...");
                $this->call('passport:install', ['--force' => true]);
            } else {
                $this->info("\nPassport already installed.");
            }
            $progressBar->advance();

            // Step 4: Create a test Passport client
            $this->info("\nCreating test Passport client...");
            $this->call('passport:client', [
                '--personal' => true,
                '--name' => 'Testing Personal Access Client'
            ]);
            $progressBar->advance();

            $progressBar->finish();

            $this->newLine(2);
            $this->info('✅ Test environment setup completed successfully.');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $progressBar->clear();
            $this->error('❌ An error occurred during test setup: ' . $e->getMessage());

            if ($this->option('verbose')) {
                $this->error('Stack trace:');
                $this->error($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }

    /**
     * Check if the specified database exists.
     *
     * @param string $databaseName
     * @return bool
     */
    protected function checkIfDatabaseExists(string $databaseName): bool
    {
        return DB::connection('testing')->getDatabaseName() === $databaseName;
    }
}
