<?php

namespace App\Application\Commands\CreateLesson;

use App\Application\Commands\CommandInterface;
use App\Application\DTOs\LessonDTO;

class CreateLessonCommand implements CommandInterface
{
    private LessonDTO $lessonDTO;

    public function __construct(
        int $skillId,
        string $name,
        string $slug,
        string $description,
        string $content,
        int $order,
        int $durationMinutes,
        int $xpReward,
        bool $isActive = true,
        bool $isPremium = false
    ) {
        $this->lessonDTO = new LessonDTO(
            skillId: $skillId,
            name: $name,
            slug: $slug,
            description: $description,
            content: $content,
            order: $order,
            durationMinutes: $durationMinutes,
            xpReward: $xpReward,
            isActive: $isActive,
            isPremium: $isPremium
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
            skillId: $data['skill_id'],
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'],
            content: $data['content'],
            order: $data['order'],
            durationMinutes: $data['duration_minutes'],
            xpReward: $data['xp_reward'],
            isActive: $data['is_active'] ?? true,
            isPremium: $data['is_premium'] ?? false
        );
    }

    /**
     * Get the lesson DTO
     * 
     * @return LessonDTO
     */
    public function getLessonDTO(): LessonDTO
    {
        return $this->lessonDTO;
    }
}
