<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\DailyGoal;
use App\Domain\Repositories\DailyGoalRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DailyGoalRepository implements DailyGoalRepositoryInterface
{
    /**
     * Find a daily goal by its primary key.
     *
     * @param int $id
     * @return DailyGoal|null
     */
    public function find(int $id): ?DailyGoal
    {
        return DailyGoal::find($id);
    }

    /**
     * Find a user's daily goal by date.
     *
     * @param int $userId
     * @param string $date
     * @return DailyGoal|null
     */
    public function findByUserAndDate(int $userId, string $date): ?DailyGoal
    {
        return DailyGoal::where('user_id', $userId)
            ->whereDate('date', $date)
            ->first();
    }

    /**
     * Get today's goal for a user.
     *
     * @param int $userId
     * @return DailyGoal|null
     */
    public function getTodayGoal(int $userId): ?DailyGoal
    {
        return $this->findByUserAndDate($userId, now()->toDateString());
    }

    /**
     * Get all daily goals.
     *
     * @return array
     */
    public function all(): array
    {
        return DailyGoal::all()->all();
    }

    /**
     * Get all goals for a specific user in descending date order.
     *
     * @param int $userId
     * @param int|null $limit
     * @return Collection
     */
    public function getByUser(int $userId, ?int $limit = null): Collection
    {
        $query = DailyGoal::where('user_id', $userId)
            ->orderBy('date', 'desc');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Get a user's daily goals within a date range.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): Collection
    {
        return DailyGoal::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get the most recent goals from users that a user is following.
     *
     * @param int $userId
     * @param int $limit
     * @return Collection
     */
    public function getFollowingGoals(int $userId, int $limit = 10): Collection
    {
        // Get IDs of users being followed
        $followingIds = \App\Domain\Models\User::find($userId)
            ->following()
            ->pluck('users.id')
            ->toArray();
            
        if (empty($followingIds)) {
            return collect();
        }
        
        return DailyGoal::whereIn('user_id', $followingIds)
            ->where('is_public', true)
            ->whereHas('user', function($query) {
                $query->where('share_daily_goals', true);
            })
            ->with('user:id,name') // Include user data
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Save a daily goal.
     *
     * @param DailyGoal $dailyGoal
     * @return DailyGoal
     */
    public function save($dailyGoal): DailyGoal
    {
        $dailyGoal->save();
        return $dailyGoal;
    }

    /**
     * Delete a daily goal.
     *
     * @param DailyGoal $dailyGoal
     * @return bool
     */
    public function delete($dailyGoal): bool
    {
        return $dailyGoal->delete();
    }

    /**
     * Toggle the visibility of a daily goal.
     *
     * @param DailyGoal $dailyGoal
     * @return bool
     */
    public function toggleVisibility(DailyGoal $dailyGoal): bool
    {
        return $dailyGoal->toggleVisibility();
    }

    /**
     * Create or update a daily goal for today.
     *
     * @param int $userId
     * @param array $data
     * @return DailyGoal
     */
    public function createOrUpdateToday(int $userId, array $data): DailyGoal
    {
        $today = now()->toDateString();
        $dailyGoal = $this->findByUserAndDate($userId, $today);
        
        if (!$dailyGoal) {
            $data['user_id'] = $userId;
            $data['date'] = $today;
            $dailyGoal = DailyGoal::create($data);
        } else {
            $dailyGoal->update($data);
        }
        
        return $dailyGoal;
    }
}
