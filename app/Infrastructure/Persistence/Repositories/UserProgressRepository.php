<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\UserProgress;
use App\Domain\Repositories\UserProgressRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserProgressRepository implements UserProgressRepositoryInterface
{
    /**
     * Find a user progress by its primary key.
     *
     * @param int $id
     * @return UserProgress|null
     */
    public function find(int $id): ?UserProgress
    {
        return UserProgress::find($id);
    }

    /**
     * Find a user progress by user ID and skill ID.
     *
     * @param int $userId
     * @param int $skillId
     * @return UserProgress|null
     */
    public function findByUserAndSkill(int $userId, int $skillId): ?UserProgress
    {
        return UserProgress::where('user_id', $userId)
            ->where('skill_id', $skillId)
            ->first();
    }

    /**
     * Get all user progress records.
     *
     * @return array
     */
    public function all(): array
    {
        return UserProgress::orderBy('user_id')
            ->orderBy('skill_id')
            ->get()
            ->all();
    }

    /**
     * Get all progress records for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getByUser(int $userId): array
    {
        return UserProgress::where('user_id', $userId)
            ->orderBy('skill_id')
            ->get()
            ->all();
    }

    /**
     * Get all user progress for a specific skill.
     *
     * @param int $skillId
     * @return array
     */
    public function getBySkill(int $skillId): array
    {
        return UserProgress::where('skill_id', $skillId)
            ->orderBy('user_id')
            ->get()
            ->all();
    }

    /**
     * Create a new user progress record.
     *
     * @param array $data
     * @return UserProgress
     */
    public function create(array $data): UserProgress
    {
        return UserProgress::create($data);
    }

    /**
     * Save a user progress.
     *
     * @param UserProgress $userProgress
     * @return UserProgress
     */
    public function save($userProgress): UserProgress
    {
        $userProgress->save();
        return $userProgress;
    }

    /**
     * Delete a user progress.
     *
     * @param UserProgress $userProgress
     * @return bool
     */
    public function delete($userProgress): bool
    {
        return $userProgress->delete();
    }

    /**
     * Get users with completion for a specific skill.
     *
     * @param int $skillId
     * @param float $minCompletion Minimum completion percentage (0-100)
     * @return array
     */
    public function getUsersWithSkillCompletion(int $skillId, float $minCompletion = 0): array
    {
        return UserProgress::where('skill_id', $skillId)
            ->where('completion_percentage', '>=', $minCompletion)
            ->orderByDesc('completion_percentage')
            ->with('user:id,name,email,xp_points,level')
            ->get()
            ->all();
    }

    /**
     * Get the most recently active skills for a user.
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getRecentlyActiveSkills(int $userId, int $limit = 5): array
    {
        return UserProgress::where('user_id', $userId)
            ->orderByDesc('last_activity_at')
            ->with('skill')
            ->limit($limit)
            ->get()
            ->all();
    }

    /**
     * Update all user progress records after a lesson completion.
     *
     * @param int $userId
     * @param int $lessonId
     * @return array Updates applied
     */
    public function updateAllAfterLessonCompletion(int $userId, int $lessonId): array
    {
        // Find the skill for this lesson
        $lesson = DB::table('lessons')->find($lessonId);
        if (!$lesson) {
            return ['success' => false, 'error' => 'Lesson not found'];
        }

        $skillId = $lesson->skill_id;
        
        // Get or create progress record
        $progress = $this->findByUserAndSkill($userId, $skillId);
        if (!$progress) {
            $progress = $this->create([
                'user_id' => $userId,
                'skill_id' => $skillId,
                'completion_percentage' => 0,
                'completed_lessons' => [],
                'last_activity_at' => now(),
            ]);
        }
        
        // Update the progress
        $progress->updateAfterLessonCompletion($lessonId);
        
        // Check if any skills were unlocked as a result
        $skill = DB::table('skills')->find($skillId);
        $unlockedSkills = [];
        
        if ($progress->completion_percentage >= 100 && $skill) {
            // Find skills that have this skill as a prerequisite
            $unlockedSkills = DB::table('skills')
                ->whereJsonContains('prerequisites', $skillId)
                ->get()
                ->all();
        }
        
        return [
            'success' => true,
            'progress' => $progress,
            'newly_unlocked_skills' => $unlockedSkills,
        ];
    }

    /**
     * Get the overall completion percentage for a user across all skills.
     *
     * @param int $userId
     * @return float
     */
    public function getOverallCompletionPercentage(int $userId): float
    {
        $progressRecords = $this->getByUser($userId);
        
        if (empty($progressRecords)) {
            return 0;
        }
        
        $totalPercentage = array_reduce($progressRecords, function ($carry, $progress) {
            return $carry + $progress->completion_percentage;
        }, 0);
        
        return $totalPercentage / count($progressRecords);
    }
}
