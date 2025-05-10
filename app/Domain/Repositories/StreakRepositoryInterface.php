<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Streak;

interface StreakRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user's streak.
     *
     * @param int $userId
     * @return Streak|null
     */
    public function findByUser(int $userId): ?Streak;

    /**
     * Get users with the longest current streaks.
     *
     * @param int $limit
     * @return array
     */
    public function getTopStreaks(int $limit = 10): array;

    /**
     * Get users who have active streaks for today.
     *
     * @return array
     */
    public function getUsersWithActiveStreaksToday(): array;

    /**
     * Get users who are at risk of losing their streaks (were active yesterday but not today).
     *
     * @return array
     */
    public function getUsersAtRiskOfLosingStreak(): array;

    /**
     * Update a user's streak based on activity.
     *
     * @param int $userId
     * @return array Result containing streak data and changes
     */
    public function updateUserStreak(int $userId): array;

    /**
     * Add streak freeze protection to a user.
     *
     * @param int $userId
     * @param int $freezeCount
     * @return bool
     */
    public function addFreezeProtection(int $userId, int $freezeCount): bool;

    /**
     * Reset a user's streak.
     *
     * @param int $userId
     * @return bool
     */
    public function resetStreak(int $userId): bool;
}
