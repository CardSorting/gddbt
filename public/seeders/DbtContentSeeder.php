<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Persistence\Seeders\LessonSeeder;
use App\Infrastructure\Persistence\Seeders\ExerciseSeeder;
use App\Infrastructure\ContentProviders\ContentProviderInterface;
use App\Infrastructure\ContentProviders\StaticContentProvider;
use App\Application\Commands\CommandBus;

class DbtContentSeeder extends Seeder
{
    private LessonSeeder $lessonSeeder;
    private ExerciseSeeder $exerciseSeeder;

    /**
     * Create a new seeder instance.
     */
    public function __construct(
        private CommandBus $commandBus
    ) {
        // Create content provider
        $contentProvider = new StaticContentProvider();
        
        // Create specialized seeders
        $this->lessonSeeder = new LessonSeeder($contentProvider, $commandBus);
        $this->exerciseSeeder = new ExerciseSeeder($contentProvider, $commandBus);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting DBT content seeding...');

        // Seed mindfulness module lessons
        $this->command->info('Seeding mindfulness module content...');
        
        // Run specialized seeders in the correct order
        $this->lessonSeeder->run();
        $this->exerciseSeeder->run();

        $this->command->info('DBT content seeding completed successfully!');
    }
}
