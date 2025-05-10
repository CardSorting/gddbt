<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Find a user by their primary key.
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }
    
    /**
     * Find multiple users by their primary keys.
     *
     * @param array $ids
     * @return array
     */
    public function findMany(array $ids): array
    {
        return User::whereIn('id', $ids)->get()->all();
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function all(): array
    {
        return User::all()->all();
    }

    /**
     * Save a user.
     *
     * @param User $user
     * @return User
     */
    public function save($user): User
    {
        $user->save();
        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     */
    public function delete($user): bool
    {
        return $user->delete();
    }

    /**
     * Get users with the most XP points (for leaderboard).
     *
     * @param int $limit
     * @return array
     */
    public function getTopByXp(int $limit = 10): array
    {
        return User::orderBy('xp_points', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'xp_points' => $user->xp_points,
                    'level' => $user->level,
                ];
            })
            ->all();
    }

    /**
     * Get users with the longest current streaks.
     *
     * @param int $limit
     * @return array
     */
    public function getTopByStreak(int $limit = 10): array
    {
        return User::join('streaks', 'users.id', '=', 'streaks.user_id')
            ->orderBy('streaks.current_count', 'desc')
            ->select('users.id', 'users.name', 'streaks.current_count as streak_count')
            ->limit($limit)
            ->get()
            ->all();
    }

    /**
     * Get users who earned a specific achievement.
     *
     * @param int $achievementId
     * @return array
     */
    public function getUsersByAchievement(int $achievementId): array
    {
        return User::join('user_achievements', 'users.id', '=', 'user_achievements.user_id')
            ->where('user_achievements.achievement_id', $achievementId)
            ->select('users.id', 'users.name', 'users.email', 'user_achievements.earned_at')
            ->orderBy('user_achievements.earned_at', 'desc')
            ->get()
            ->all();
    }

    /**
     * Get users who have completed a specific skill.
     *
     * @param int $skillId
     * @return array
     */
    public function getUsersByCompletedSkill(int $skillId): array
    {
        return User::join('user_progress', 'users.id', '=', 'user_progress.user_id')
            ->where('user_progress.skill_id', $skillId)
            ->where('user_progress.completion_percentage', 100)
            ->select('users.id', 'users.name', 'users.email', 'user_progress.last_activity_at')
            ->orderBy('user_progress.last_activity_at', 'desc')
            ->get()
            ->all();
    }
}
