<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Achievement;

interface AchievementRepositoryInterface extends RepositoryInterface
{
    /**
     * Find an achievement by its slug.
     *
     * @param string $slug
     * @return Achievement|null
     */
    public function findBySlug(string $slug): ?Achievement;

    /**
     * Get achievements by category.
     *
     * @param string $category
     * @param bool $activeOnly
     * @return array
     */
    public function getByCategory(string $category, bool $activeOnly = true): array;

    /**
     * Get achievements earned by a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getEarnedByUser(int $userId): array;

    /**
     * Get achievements not yet earned by a specific user.
     *
     * @param int $userId
     * @param bool $includeHidden Whether to include hidden achievements
     * @return array
     */
    public function getNotEarnedByUser(int $userId, bool $includeHidden = false): array;

    /**
     * Check for and award any achievements a user has qualified for.
     *
     * @param int $userId
     * @return array The IDs of any newly awarded achievements
     */
    public function checkAndAwardAchievements(int $userId): array;

    /**
     * Record a user earning an achievement.
     *
     * @param int $userId
     * @param int $achievementId
     * @return bool
     */
    public function recordAchievementEarned(int $userId, int $achievementId): bool;
}
