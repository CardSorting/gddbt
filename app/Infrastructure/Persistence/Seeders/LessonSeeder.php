<?php

namespace App\Infrastructure\Persistence\Seeders;

use App\Application\Commands\CreateLesson\CreateLessonCommand;
use App\Domain\Models\Skill;
use Illuminate\Support\Facades\Log;

class LessonSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info('Seeding lessons...');

        // Create a map of skill slugs to IDs
        $skills = Skill::all();
        $skillMap = $this->createSlugToIdMap($skills);

        // Get lesson data from content provider
        $lessonsData = $this->contentProvider->getLessonsData();
        
        // Count for info display
        $total = count($lessonsData);
        $current = 0;

        foreach ($lessonsData as $lessonData) {
            $current++;
            
            // Look up the skill ID from the slug
            $skillSlug = $lessonData['skill_slug'];
            $skillId = $this->getSkillId($skillSlug, $skillMap);
            
            if (!$skillId) {
                Log::warning("Skill with slug '{$skillSlug}' not found when seeding lesson '{$lessonData['name']}'");
                continue;
            }

            $this->command->info("Seeding lesson ({$current}/{$total}): {$lessonData['name']}");
            
            // Check if lesson already exists
            $existingLesson = \App\Domain\Models\Lesson::where('slug', $lessonData['slug'])->first();
            
            if ($existingLesson) {
                $this->command->info("  - Lesson already exists, skipping");
                continue;
            }
            
            // Create and dispatch the command
            $command = new CreateLessonCommand(
                skillId: $skillId,
                name: $lessonData['name'],
                slug: $lessonData['slug'],
                description: $lessonData['description'],
                content: $lessonData['content'],
                order: $lessonData['order'],
                durationMinutes: $lessonData['duration_minutes'],
                xpReward: $lessonData['xp_reward'],
                isActive: $lessonData['is_active'] ?? true,
                isPremium: $lessonData['is_premium'] ?? false
            );
            
            $this->commandBus->dispatch($command);
        }

        $this->command->info('Lessons seeded successfully!');
    }
}
