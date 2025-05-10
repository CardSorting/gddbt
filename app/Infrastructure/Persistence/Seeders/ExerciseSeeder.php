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
            
            // Check if exercise already exists (using lesson ID and order as unique identifiers)
            $existingExercise = \App\Domain\Models\Exercise::where('lesson_id', $lessonId)
                ->where('order', $exerciseData['order'])
                ->first();
            
            if ($existingExercise) {
                $this->command->info("  - Exercise already exists, updating content");
                
                // Update the existing exercise with new content
                $existingExercise->title = $exerciseData['title'];
                $existingExercise->description = $exerciseData['description'];
                $existingExercise->type = $exerciseData['type'];
                $existingExercise->content = $exerciseData['content'];
                $existingExercise->options = $exerciseData['options'] ?? null;
                $existingExercise->correct_answer = $exerciseData['correct_answer'] ?? null;
                $existingExercise->order = $exerciseData['order'];
                $existingExercise->difficulty = $exerciseData['difficulty'];
                $existingExercise->xp_reward = $exerciseData['xp_reward'];
                $existingExercise->is_active = $exerciseData['is_active'] ?? true;
                
                // Save the updated exercise
                $exerciseRepository = new \App\Infrastructure\Persistence\Repositories\ExerciseRepository();
                $exerciseRepository->save($existingExercise);
                continue;
            }
            
            // If exercise doesn't exist, create a new one
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
