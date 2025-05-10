<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\Streak;
use App\Domain\Events\StreakUpdatedEvent;
use App\Domain\Repositories\StreakRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StreakRepository implements StreakRepositoryInterface
{
    /**
     * Find a streak by its primary key.
     *
     * @param int $id
     * @return Streak|null
     */
    public function find(int $id): ?Streak
    {
        return Streak::find($id);
    }

    /**
     * Find a user's streak.
     *
     * @param int $userId
     * @return Streak|null
     */
    public function findByUser(int $userId): ?Streak
    {
        return Streak::where('user_id', $userId)->first();
    }

    /**
     * Get all streaks.
     *
     * @return array
     */
    public function all(): array
    {
        return Streak::all()->all();
    }

    /**
     * Get users with the longest current streaks.
     *
     * @param int $limit
     * @return array
     */
    public function getTopStreaks(int $limit = 10): array
    {
        return Streak::join('users', 'streaks.user_id', '=', 'users.id')
            ->orderBy('streaks.current_count', 'desc')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'streaks.current_count',
                'streaks.longest_count',
                'streaks.last_activity_date'
            )
            ->limit($limit)
            ->get()
            ->all();
    }

    /**
     * Get users who have active streaks for today.
     *
     * @return array
     */
    public function getUsersWithActiveStreaksToday(): array
    {
        $today = Carbon::today();
        
        return Streak::join('users', 'streaks.user_id', '=', 'users.id')
            ->whereDate('streaks.last_activity_date', $today)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'streaks.current_count',
                'streaks.last_activity_date'
            )
            ->orderBy('streaks.current_count', 'desc')
            ->get()
            ->all();
    }

    /**
     * Get users who are at risk of losing their streaks (were active yesterday but not today).
     *
     * @return array
     */
    public function getUsersAtRiskOfLosingStreak(): array
    {
        $yesterday = Carbon::yesterday();
        $today = Carbon::today();
        
        return Streak::join('users', 'streaks.user_id', '=', 'users.id')
            ->whereDate('streaks.last_activity_date', $yesterday)
            ->whereNotExists(function ($query) use ($today) {
                $query->select(DB::raw(1))
                    ->from('user_lesson_completions')
                    ->whereRaw('user_lesson_completions.user_id = streaks.user_id')
                    ->whereDate('user_lesson_completions.completed_at', $today);
            })
            ->whereNotExists(function ($query) use ($today) {
                $query->select(DB::raw(1))
                    ->from('user_exercise_completions')
                    ->whereRaw('user_exercise_completions.user_id = streaks.user_id')
                    ->whereDate('user_exercise_completions.completed_at', $today);
            })
            ->where('streaks.current_count', '>', 1) // Only count meaningful streaks
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'streaks.current_count',
                'streaks.last_activity_date'
            )
            ->orderBy('streaks.current_count', 'desc')
            ->get()
            ->all();
    }

    /**
     * Save a streak.
     *
     * @param Streak $streak
     * @return Streak
     */
    public function save($streak): Streak
    {
        $streak->save();
        return $streak;
    }

    /**
     * Delete a streak.
     *
     * @param Streak $streak
     * @return bool
     */
    public function delete($streak): bool
    {
        return $streak->delete();
    }

    /**
     * Update a user's streak based on activity.
     *
     * @param int $userId
     * @return array Result containing streak data and changes
     */
    public function updateUserStreak(int $userId): array
    {
        // Get or create the user's streak
        $streak = $this->findByUser($userId);
        
        if (!$streak) {
            $streak = new Streak([
                'user_id' => $userId,
                'current_count' => 0,
                'longest_count' => 0,
                'last_activity_date' => null,
                'freeze_count' => 0,
            ]);
            $streak->save();
        }
        
        // Update the streak
        $result = $streak->updateStreak();
        
        // Dispatch domain event for streak update
        if ($result['increased']) {
            event(new StreakUpdatedEvent(
                $userId,
                $result['new_count'],
                true,
                $result['milestone'] ?? false
            ));
        } elseif (isset($result['streak_reset']) && $result['streak_reset']) {
            event(new StreakUpdatedEvent(
                $userId,
                $result['new_count'],
                false,
                false
            ));
        }
        
        return $result;
    }

    /**
     * Add streak freeze protection to a user.
     *
     * @param int $userId
     * @param int $freezeCount
     * @return bool
     */
    public function addFreezeProtection(int $userId, int $freezeCount): bool
    {
        $streak = $this->findByUser($userId);
        
        if (!$streak) {
            $streak = new Streak([
                'user_id' => $userId,
                'current_count' => 0,
                'longest_count' => 0,
                'last_activity_date' => null,
                'freeze_count' => 0,
            ]);
            $streak->save();
        }
        
        $newFreezeCount = $streak->addFreezeProtection($freezeCount);
        
        return true;
    }

    /**
     * Reset a user's streak.
     *
     * @param int $userId
     * @return bool
     */
    public function resetStreak(int $userId): bool
    {
        $streak = $this->findByUser($userId);
        
        if ($streak) {
            $streak->current_count = 0;
            $streak->last_activity_date = null;
            $streak->save();
        }
        
        return true;
    }
}
