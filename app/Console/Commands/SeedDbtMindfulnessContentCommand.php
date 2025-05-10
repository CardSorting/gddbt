<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DbtContentSeeder;

class SeedDbtMindfulnessContentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbt:seed-mindfulness';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the DBT mindfulness module content into the database';

    /**
     * Execute the console command.
     */
    public function handle(DbtContentSeeder $seeder)
    {
        $this->info('Starting to seed DBT mindfulness module content...');
        
        try {
            $seeder->setCommand($this);
            $seeder->run();
            
            $this->info('DBT mindfulness content has been seeded successfully!');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to seed DBT mindfulness content: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            
            return Command::FAILURE;
        }
    }
}
