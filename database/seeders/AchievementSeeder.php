<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Streak Achievements
        Achievement::create([
            'name' => 'First Day',
            'slug' => 'first-day',
            'description' => 'Complete your first day of learning.',
            'icon' => 'calendar-check',
            'category' => Achievement::CATEGORY_STREAK,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 10,
            'requirements' => ['streak_days' => 1],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'One Week Streak',
            'slug' => 'one-week-streak',
            'description' => 'Complete a 7-day streak of learning.',
            'icon' => 'calendar-week',
            'category' => Achievement::CATEGORY_STREAK,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 25,
            'requirements' => ['streak_days' => 7],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'One Month Streak',
            'slug' => 'one-month-streak',
            'description' => 'Complete a 30-day streak of learning.',
            'icon' => 'calendar-alt',
            'category' => Achievement::CATEGORY_STREAK,
            'level' => Achievement::LEVEL_SILVER,
            'xp_reward' => 100,
            'requirements' => ['streak_days' => 30],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Lesson Achievements
        Achievement::create([
            'name' => 'First Lesson',
            'slug' => 'first-lesson',
            'description' => 'Complete your first lesson.',
            'icon' => 'book-open',
            'category' => Achievement::CATEGORY_LESSON,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 15,
            'requirements' => ['lesson_count' => 1],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'Dedicated Learner',
            'slug' => 'dedicated-learner',
            'description' => 'Complete 10 lessons.',
            'icon' => 'graduation-cap',
            'category' => Achievement::CATEGORY_LESSON,
            'level' => Achievement::LEVEL_SILVER,
            'xp_reward' => 50,
            'requirements' => ['lesson_count' => 10],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Skill Achievements - Mindfulness
        Achievement::create([
            'name' => 'Mindfulness Beginner',
            'slug' => 'mindfulness-beginner',
            'description' => 'Complete your first mindfulness skill.',
            'icon' => 'brain',
            'category' => Achievement::CATEGORY_SKILL,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 30,
            'requirements' => ['skill_ids' => [1]],  // Assuming Wise Mind is skill ID 1
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Skill Achievements - Distress Tolerance
        Achievement::create([
            'name' => 'Distress Tolerance Beginner',
            'slug' => 'distress-tolerance-beginner',
            'description' => 'Complete your first distress tolerance skill.',
            'icon' => 'shield-alt',
            'category' => Achievement::CATEGORY_SKILL,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 30,
            'requirements' => ['skill_ids' => [3]],  // Assuming TIPP is skill ID 3
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Skill Achievements - Emotion Regulation
        Achievement::create([
            'name' => 'Emotion Regulation Beginner',
            'slug' => 'emotion-regulation-beginner',
            'description' => 'Complete your first emotion regulation skill.',
            'icon' => 'heart',
            'category' => Achievement::CATEGORY_SKILL,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 30,
            'requirements' => ['skill_ids' => [4]],  // Assuming Identifying Emotions is skill ID 4
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Skill Achievements - Interpersonal Effectiveness
        Achievement::create([
            'name' => 'Interpersonal Effectiveness Beginner',
            'slug' => 'interpersonal-effectiveness-beginner',
            'description' => 'Complete your first interpersonal effectiveness skill.',
            'icon' => 'users',
            'category' => Achievement::CATEGORY_SKILL,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 30,
            'requirements' => ['skill_ids' => [5]],  // Assuming DEAR MAN is skill ID 5
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Module Achievements
        Achievement::create([
            'name' => 'Mindfulness Master',
            'slug' => 'mindfulness-master',
            'description' => 'Complete all skills in the Mindfulness module.',
            'icon' => 'star',
            'category' => Achievement::CATEGORY_MODULE,
            'level' => Achievement::LEVEL_GOLD,
            'xp_reward' => 100,
            'requirements' => ['module_ids' => [1]],  // Mindfulness module
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'Distress Tolerance Master',
            'slug' => 'distress-tolerance-master',
            'description' => 'Complete all skills in the Distress Tolerance module.',
            'icon' => 'star',
            'category' => Achievement::CATEGORY_MODULE,
            'level' => Achievement::LEVEL_GOLD,
            'xp_reward' => 100,
            'requirements' => ['module_ids' => [2]],  // Distress Tolerance module
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'Emotion Regulation Master',
            'slug' => 'emotion-regulation-master',
            'description' => 'Complete all skills in the Emotion Regulation module.',
            'icon' => 'star',
            'category' => Achievement::CATEGORY_MODULE,
            'level' => Achievement::LEVEL_GOLD,
            'xp_reward' => 100,
            'requirements' => ['module_ids' => [3]],  // Emotion Regulation module
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'Interpersonal Effectiveness Master',
            'slug' => 'interpersonal-effectiveness-master',
            'description' => 'Complete all skills in the Interpersonal Effectiveness module.',
            'icon' => 'star',
            'category' => Achievement::CATEGORY_MODULE,
            'level' => Achievement::LEVEL_GOLD,
            'xp_reward' => 100,
            'requirements' => ['module_ids' => [4]],  // Interpersonal Effectiveness module
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Perfection Achievements
        Achievement::create([
            'name' => 'Perfect Score',
            'slug' => 'perfect-score',
            'description' => 'Complete a lesson with a 100% score.',
            'icon' => 'award',
            'category' => Achievement::CATEGORY_PERFECTION,
            'level' => Achievement::LEVEL_BRONZE,
            'xp_reward' => 20,
            'requirements' => ['perfect_lesson_count' => 1],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Achievement::create([
            'name' => 'Perfectionist',
            'slug' => 'perfectionist',
            'description' => 'Complete 5 lessons with a 100% score.',
            'icon' => 'trophy',
            'category' => Achievement::CATEGORY_PERFECTION,
            'level' => Achievement::LEVEL_SILVER,
            'xp_reward' => 75,
            'requirements' => ['perfect_lesson_count' => 5],
            'is_active' => true,
            'is_hidden' => false,
        ]);

        // Hidden achievement for completing all modules
        Achievement::create([
            'name' => 'DBT Champion',
            'slug' => 'dbt-champion',
            'description' => 'Complete all modules in the DBT program.',
            'icon' => 'crown',
            'category' => Achievement::CATEGORY_SPECIAL,
            'level' => Achievement::LEVEL_PLATINUM,
            'xp_reward' => 500,
            'requirements' => ['module_ids' => [1, 2, 3, 4]],  // All modules
            'is_active' => true,
            'is_hidden' => true,
        ]);
    }
}
