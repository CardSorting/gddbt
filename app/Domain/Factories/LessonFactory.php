<?php

namespace App\Domain\Factories;

use App\Domain\Models\Lesson;
use App\Domain\ValueObjects\Content\LessonContent;

class LessonFactory
{
    /**
     * Create a new Lesson instance with proper value objects.
     *
     * @param int $skillId
     * @param string $name
     * @param string $slug
     * @param string $description
     * @param LessonContent|string $content
     * @param int $order
     * @param int $durationMinutes
     * @param int $xpReward
     * @param bool $isActive
     * @param bool $isPremium
     * @return Lesson
     */
    public function create(
        int $skillId,
        string $name,
        string $slug,
        string $description,
        $content,
        int $order,
        int $durationMinutes,
        int $xpReward,
        bool $isActive = true,
        bool $isPremium = false
    ): Lesson {
        // Convert string content to LessonContent if needed
        if (is_string($content)) {
            $content = new LessonContent($content);
        }

        // Calculate duration if not provided based on content
        if ($durationMinutes <= 0) {
            $durationMinutes = $content->estimatedReadingTimeMinutes();
        }

        // Create the lesson with proper attributes
        $lesson = new Lesson();
        $lesson->fill([
            'skill_id' => $skillId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'content' => (string) $content,
            'order' => $order,
            'duration_minutes' => $durationMinutes,
            'xp_reward' => $xpReward,
            'is_active' => $isActive,
            'is_premium' => $isPremium
        ]);

        return $lesson;
    }
}
