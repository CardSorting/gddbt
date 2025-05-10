<?php

namespace App\Domain\Repositories;

use App\Domain\Models\DailyGoal;
use Illuminate\Support\Collection;

interface DailyGoalRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user's daily goal by date.
     *
     * @param int $userId
     * @param string $date
     * @return DailyGoal|null
     */
    public function findByUserAndDate(int $userId, string $date): ?DailyGoal;

    /**
     * Get today's goal for a user.
     *
     * @param int $userId
     * @return DailyGoal|null
     */
    public function getTodayGoal(int $userId): ?DailyGoal;

    /**
     * Get all goals for a specific user in descending date order.
     *
     * @param int $userId
     * @param int|null $limit
     * @return Collection
     */
    public function getByUser(int $userId, ?int $limit = null): Collection;

    /**
     * Get a user's daily goals within a date range.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): Collection;

    /**
     * Get the most recent goals from users that a user is following.
     *
     * @param int $userId
     * @param int $limit
     * @return Collection
     */
    public function getFollowingGoals(int $userId, int $limit = 10): Collection;

    /**
     * Toggle the visibility of a daily goal.
     *
     * @param DailyGoal $dailyGoal
     * @return bool
     */
    public function toggleVisibility(DailyGoal $dailyGoal): bool;
}
