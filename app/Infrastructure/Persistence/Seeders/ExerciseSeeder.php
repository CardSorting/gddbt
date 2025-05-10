<?php

namespace App\Infrastructure\Persistence\Seeders;

use App\Application\Commands\CreateExercise\CreateExerciseCommand;
use App\Domain\Models\Lesson;
use Illuminate\Support\Facades\Log;

class ExerciseSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info('Seeding exercises...');

        // Create a map of lesson slugs to IDs
        $lessons = Lesson::all();
        $lessonMap = $this->createSlugToIdMap($lessons);

        // Get exercise data from content provider
        $exercisesData = $this->contentProvider->getExercisesData();
        
        // Count for info display
        $total = count($exercisesData);
        $current = 0;

        foreach ($exercisesData as $exerciseData) {
            $current++;
            
            // Look up the lesson ID from the slug
            $lessonSlug = $exerciseData['lesson_slug'];
            $lessonId = $this->getLessonId($lessonSlug, $lessonMap);
            
            if (!$lessonId) {
                Log::warning("Lesson with slug '{$lessonSlug}' not found when seeding exercise '{$exerciseData['title']}'");
                continue;
            }

            $this->command->info("Seeding exercise ({$current}/{$total}): {$exerciseData['title']}");
            
            // Create and dispatch the command
            $command = new CreateExerciseCommand(
                lessonId: $lessonId,
                title: $exerciseData['title'],
                description: $exerciseData['description'],
                type: $exerciseData['type'],
                content: $exerciseData['content'],
                options: $exerciseData['options'] ?? null,
                correctAnswer: $exerciseData['correct_answer'] ?? null,
                order: $exerciseData['order'],
                difficulty: $exerciseData['difficulty'],
                xpReward: $exerciseData['xp_reward'],
                isActive: $exerciseData['is_active'] ?? true
            );
            
            $this->commandBus->dispatch($command);
        }

        $this->command->info('Exercises seeded successfully!');
    }
}
