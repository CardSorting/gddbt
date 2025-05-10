<?php

namespace App\Domain\Repositories;

use App\Domain\Models\UserProgress;

interface UserProgressRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user progress by user ID and skill ID.
     *
     * @param int $userId
     * @param int $skillId
     * @return UserProgress|null
     */
    public function findByUserAndSkill(int $userId, int $skillId): ?UserProgress;

    /**
     * Get all progress records for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getByUser(int $userId): array;

    /**
     * Get all user progress for a specific skill.
     *
     * @param int $skillId
     * @return array
     */
    public function getBySkill(int $skillId): array;

    /**
     * Create a new user progress record.
     *
     * @param array $data
     * @return UserProgress
     */
    public function create(array $data): UserProgress;

    /**
     * Get users with completion for a specific skill.
     *
     * @param int $skillId
     * @param float $minCompletion Minimum completion percentage (0-100)
     * @return array
     */
    public function getUsersWithSkillCompletion(int $skillId, float $minCompletion = 0): array;

    /**
     * Get the most recently active skills for a user.
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getRecentlyActiveSkills(int $userId, int $limit = 5): array;

    /**
     * Update all user progress records after a lesson completion.
     *
     * @param int $userId
     * @param int $lessonId
     * @return array Updates applied
     */
    public function updateAllAfterLessonCompletion(int $userId, int $lessonId): array;

    /**
     * Get the overall completion percentage for a user across all skills.
     *
     * @param int $userId
     * @return float
     */
    public function getOverallCompletionPercentage(int $userId): float;
}
