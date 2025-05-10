<?php

namespace App\Infrastructure\ContentProviders;

class StaticContentProvider implements ContentProviderInterface
{
    /**
     * Get modules data.
     * 
     * @return array
     */
    public function getModulesData(): array
    {
        return [
            [
                'name' => 'Mindfulness',
                'slug' => 'mindfulness',
                'description' => 'The foundation of all DBT skills. Mindfulness helps you observe, describe, and participate in the present moment without judgment.',
                'icon' => 'meditation',
                'color_code' => '#4CAF50',
                'order' => 1,
                'is_active' => true,
            ]
        ];
    }

    /**
     * Get skills data.
     * 
     * @return array
     */
    public function getSkillsData(): array
    {
        return [
            [
                'module_slug' => 'mindfulness',
                'name' => 'Wise Mind',
                'slug' => 'wise-mind',
                'description' => 'Balancing emotional mind and reasonable mind to access wisdom and intuition.',
                'icon' => 'balance-scale',
                'order' => 1,
                'is_active' => true,
                'is_premium' => false,
                'prerequisites' => [],
            ]
        ];
    }

    /**
     * Get lessons data.
     * 
     * @return array
     */
    public function getLessonsData(): array
    {
        return [
            [
                'skill_slug' => 'wise-mind',
                'name' => 'Introduction to Wise Mind',
                'slug' => 'intro-to-wise-mind',
                'description' => 'Learn about the three states of mind: emotional mind, reasonable mind, and wise mind.',
                'content' => $this->getLessonContent('intro-to-wise-mind'),
                'order' => 1,
                'duration_minutes' => 10,
                'xp_reward' => 15,
                'is_active' => true,
                'is_premium' => false,
            ]
        ];
    }

    /**
     * Get exercises data.
     * 
     * @return array
     */
    public function getExercisesData(): array
    {
        return [
            [
                'lesson_slug' => 'intro-to-wise-mind',
                'title' => 'Identify the State of Mind',
                'description' => 'Read each scenario and identify which state of mind is being described.',
                'type' => 'multiple_choice',
                'content' => 'When Sarah received criticism at work, she immediately felt hurt and sent an angry email to her boss without thinking about the consequences.',
                'options' => ['Emotional Mind', 'Reasonable Mind', 'Wise Mind'],
                'correct_answer' => ['Emotional Mind'],
                'order' => 1,
                'difficulty' => 1,
                'xp_reward' => 5,
                'is_active' => true,
            ]
        ];
    }

    /**
     * Get content for a specific lesson.
     * 
     * @param string $lessonSlug The lesson slug
     * @return string|null The lesson content or null if not found
     */
    public function getLessonContent(string $lessonSlug): ?string
    {
        $content = [
            'intro-to-wise-mind' => 'Wise Mind is the integration of emotional mind and reasonable mind. It\'s a state of mind where you can access your intuition and inner wisdom.'
        ];

        return $content[$lessonSlug] ?? null;
    }

    /**
     * Get content for a specific exercise.
     * 
     * @param string $exerciseSlug The exercise slug
     * @return array|null The exercise content data or null if not found
     */
    public function getExerciseContent(string $exerciseSlug): ?array
    {
        return null;
    }
}
