<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\Achievement;
use App\Domain\Events\AchievementEarnedEvent;
use App\Domain\Repositories\AchievementRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AchievementRepository implements AchievementRepositoryInterface
{
    /**
     * Find an achievement by its primary key.
     *
     * @param int $id
     * @return Achievement|null
     */
    public function find(int $id): ?Achievement
    {
        return Achievement::find($id);
    }

    /**
     * Find an achievement by its slug.
     *
     * @param string $slug
     * @return Achievement|null
     */
    public function findBySlug(string $slug): ?Achievement
    {
        return Achievement::where('slug', $slug)->first();
    }

    /**
     * Get all achievements.
     *
     * @return array
     */
    public function all(): array
    {
        return Achievement::orderBy('category')
            ->orderBy('level')
            ->get()
            ->all();
    }

    /**
     * Get achievements by category.
     *
     * @param string $category
     * @param bool $activeOnly
     * @return array
     */
    public function getByCategory(string $category, bool $activeOnly = true): array
    {
        $query = Achievement::where('category', $category);
        
        if ($activeOnly) {
            $query->where('is_active', true);
        }
        
        return $query->orderBy('level')
            ->get()
            ->all();
    }

    /**
     * Save an achievement.
     *
     * @param Achievement $achievement
     * @return Achievement
     */
    public function save($achievement): Achievement
    {
        $achievement->save();
        return $achievement;
    }

    /**
     * Delete an achievement.
     *
     * @param Achievement $achievement
     * @return bool
     */
    public function delete($achievement): bool
    {
        return $achievement->delete();
    }

    /**
     * Get achievements earned by a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getEarnedByUser(int $userId): array
    {
        return Achievement::join('user_achievements', 'achievements.id', '=', 'user_achievements.achievement_id')
            ->where('user_achievements.user_id', $userId)
            ->select(
                'achievements.*',
                'user_achievements.earned_at'
            )
            ->orderBy('user_achievements.earned_at', 'desc')
            ->get()
            ->all();
    }

    /**
     * Get achievements not yet earned by a specific user.
     *
     * @param int $userId
     * @param bool $includeHidden Whether to include hidden achievements
     * @return array
     */
    public function getNotEarnedByUser(int $userId, bool $includeHidden = false): array
    {
        $query = Achievement::whereNotExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                ->from('user_achievements')
                ->whereRaw('user_achievements.achievement_id = achievements.id')
                ->where('user_achievements.user_id', $userId);
        })
        ->where('is_active', true);
        
        if (!$includeHidden) {
            $query->where('is_hidden', false);
        }
        
        return $query->orderBy('category')
            ->orderBy('level')
            ->get()
            ->all();
    }

    /**
     * Check for and award any achievements a user has qualified for.
     *
     * @param int $userId
     * @return array The IDs of any newly awarded achievements
     */
    public function checkAndAwardAchievements(int $userId): array
    {
        $user = DB::table('users')->find($userId);
        if (!$user) {
            return [];
        }

        // Get all achievements not yet earned by the user
        $unearnedAchievements = $this->getNotEarnedByUser($userId, true);
        $newlyAwardedIds = [];
        
        foreach ($unearnedAchievements as $achievement) {
            if ($achievement->checkRequirements($user)) {
                $this->recordAchievementEarned($userId, $achievement->id);
                $newlyAwardedIds[] = $achievement->id;
                
                // Award XP for the achievement
                if ($achievement->xp_reward > 0) {
                    DB::table('users')
                        ->where('id', $userId)
                        ->increment('xp_points', $achievement->xp_reward);
                    
                    // Recalculate user level (simplified formula)
                    $newXp = $user->xp_points + $achievement->xp_reward;
                    $newLevel = floor(sqrt($newXp / 100));
                    
                    if ($newLevel > $user->level) {
                        DB::table('users')
                            ->where('id', $userId)
                            ->update(['level' => $newLevel]);
                    }
                }
                
                // Dispatch domain event
                event(new AchievementEarnedEvent($userId, $achievement->id));
            }
        }
        
        return $newlyAwardedIds;
    }

    /**
     * Record a user earning an achievement.
     *
     * @param int $userId
     * @param int $achievementId
     * @return bool
     */
    public function recordAchievementEarned(int $userId, int $achievementId): bool
    {
        // Check if already earned
        $exists = DB::table('user_achievements')
            ->where('user_id', $userId)
            ->where('achievement_id', $achievementId)
            ->exists();
            
        if ($exists) {
            return false;
        }

        // Record the achievement
        DB::table('user_achievements')->insert([
            'user_id' => $userId,
            'achievement_id' => $achievementId,
            'earned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return true;
    }
}
