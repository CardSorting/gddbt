<?php

namespace App\Application\DTOs;

class LessonDTO
{
    public function __construct(
        public readonly int $skillId,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $content,
        public readonly int $order,
        public readonly int $durationMinutes,
        public readonly int $xpReward,
        public readonly bool $isActive = true,
        public readonly bool $isPremium = false
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
     * Convert to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'skill_id' => $this->skillId,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'order' => $this->order,
            'duration_minutes' => $this->durationMinutes,
            'xp_reward' => $this->xpReward,
            'is_active' => $this->isActive,
            'is_premium' => $this->isPremium,
        ];
    }
}
