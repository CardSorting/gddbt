<?php

namespace App\Domain\Repositories;

use App\Domain\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get users with the most XP points (for leaderboard).
     *
     * @param int $limit
     * @return array
     */
    public function getTopByXp(int $limit = 10): array;

    /**
     * Get users with the longest current streaks.
     *
     * @param int $limit
     * @return array
     */
    public function getTopByStreak(int $limit = 10): array;

    /**
     * Get users who earned a specific achievement.
     *
     * @param int $achievementId
     * @return array
     */
    public function getUsersByAchievement(int $achievementId): array;

    /**
     * Get users who have completed a specific skill.
     *
     * @param int $skillId
     * @return array
     */
    public function getUsersByCompletedSkill(int $skillId): array;
}
