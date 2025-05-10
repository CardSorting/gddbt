<?php

namespace App\Application\Commands\CreateExercise;

use App\Application\Commands\CommandInterface;
use App\Application\DTOs\ExerciseDTO;

class CreateExerciseCommand implements CommandInterface
{
    private ExerciseDTO $exerciseDTO;

    public function __construct(
        int $lessonId,
        string $title,
        string $description,
        string $type,
        string $content,
        ?array $options,
        ?array $correctAnswer,
        int $order,
        int $difficulty,
        int $xpReward,
        bool $isActive = true
    ) {
        $this->exerciseDTO = new ExerciseDTO(
            lessonId: $lessonId,
            title: $title,
            description: $description,
            type: $type,
            content: $content,
            options: $options,
            correctAnswer: $correctAnswer,
            order: $order,
            difficulty: $difficulty,
            xpReward: $xpReward,
            isActive: $isActive
        );
    }

    /**
     * Create command from array data
     * 
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            lessonId: $data['lesson_id'],
            title: $data['title'],
            description: $data['description'],
            type: $data['type'],
            content: $data['content'],
            options: $data['options'] ?? null,
            correctAnswer: $data['correct_answer'] ?? null,
            order: $data['order'],
            difficulty: $data['difficulty'],
            xpReward: $data['xp_reward'],
            isActive: $data['is_active'] ?? true
        );
    }

    /**
     * Get the exercise DTO
     * 
     * @return ExerciseDTO
     */
    public function getExerciseDTO(): ExerciseDTO
    {
        return $this->exerciseDTO;
    }
    
    /**
     * Get the command type (used for logging and tracing).
     *
     * @return string
     */
    public function getCommandType(): string
    {
        return 'create_exercise';
    }
}
