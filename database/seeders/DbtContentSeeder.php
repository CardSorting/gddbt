<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Persistence\Seeders\LessonSeeder;
use App\Infrastructure\Persistence\Seeders\ExerciseSeeder;
use App\Infrastructure\ContentProviders\ContentProviderInterface;
use App\Application\Commands\CommandBus;

class DbtContentSeeder extends Seeder
{
    private LessonSeeder $lessonSeeder;
    private ExerciseSeeder $exerciseSeeder;
    private ContentProviderInterface $contentProvider;

    /**
     * Create a new seeder instance.
     */
    public function __construct(
        ContentProviderInterface $contentProvider,
        private CommandBus $commandBus
    ) {
        $this->contentProvider = $contentProvider;
        
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
        
        // Pass command to child seeders
        $this->lessonSeeder->setCommand($this->command);
        $this->exerciseSeeder->setCommand($this->command);
        
        // Run specialized seeders in the correct order
        $this->lessonSeeder->run();
        $this->exerciseSeeder->run();

        $this->command->info('DBT content seeding completed successfully!');
    }
}
