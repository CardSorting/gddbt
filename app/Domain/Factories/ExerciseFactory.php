<?php

namespace App\Domain\Factories;

use App\Domain\Models\Exercise;
use App\Domain\ValueObjects\Content\ExerciseContent;

class ExerciseFactory
{
    /**
     * Create a new Exercise instance with proper value objects.
     *
     * @param int $lessonId
     * @param string $title
     * @param string $description
     * @param string $type
     * @param ExerciseContent|string $content
     * @param array|null $options
     * @param array|null $correctAnswer
     * @param int $order
     * @param int $difficulty
     * @param int $xpReward
     * @param bool $isActive
     * @return Exercise
     */
    public function create(
        int $lessonId,
        string $title,
        string $description,
        string $type,
        $content,
        ?array $options = null,
        ?array $correctAnswer = null,
        int $order = 1,
        int $difficulty = 1,
        int $xpReward = 10,
        bool $isActive = true
    ): Exercise {
        // Convert string content to ExerciseContent if needed
        if (is_string($content)) {
            $content = new ExerciseContent($content, $options, $correctAnswer);
        }

        // Scale XP reward based on difficulty if not explicitly provided
        if ($xpReward <= 0) {
            $xpReward = 5 * $difficulty;
        }

        // Create the exercise with proper attributes
        $exercise = new Exercise();
        $exercise->fill([
            'lesson_id' => $lessonId,
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'content' => $content->getContent(),
            'options' => $content->getOptions(),
            'correct_answer' => $content->getCorrectAnswer(),
            'order' => $order,
            'difficulty' => $difficulty,
            'xp_reward' => $xpReward,
            'is_active' => $isActive,
        ]);

        return $exercise;
    }
}
