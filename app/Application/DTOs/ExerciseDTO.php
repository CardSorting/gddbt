<?php

namespace App\Application\DTOs;

class ExerciseDTO
{
    public function __construct(
        public readonly int $lessonId,
        public readonly string $title,
        public readonly string $description,
        public readonly string $type,
        public readonly string $content,
        public readonly ?array $options,
        public readonly ?array $correctAnswer,
        public readonly int $order,
        public readonly int $difficulty,
        public readonly int $xpReward,
        public readonly bool $isActive = true
    ) {}

    /**
     * Create a DTO from array data
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
     * Convert to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'lesson_id' => $this->lessonId,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'content' => $this->content,
            'options' => $this->options,
            'correct_answer' => $this->correctAnswer,
            'order' => $this->order,
            'difficulty' => $this->difficulty,
            'xp_reward' => $this->xpReward,
            'is_active' => $this->isActive,
        ];
    }
}
