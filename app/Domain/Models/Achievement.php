<?php

namespace App\Domain\Models;

class Achievement extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'level',
        'xp_reward',
        'requirements',
        'is_active',
        'is_hidden',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level' => 'integer',
        'xp_reward' => 'integer',
        'requirements' => 'array',
        'is_active' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    /**
     * Achievement categories
     */
    const CATEGORY_STREAK = 'streak';
    const CATEGORY_LESSON = 'lesson';
    const CATEGORY_SKILL = 'skill';
    const CATEGORY_MODULE = 'module';
    const CATEGORY_PERFECTION = 'perfection';
    const CATEGORY_SPECIAL = 'special';

    /**
     * Achievement levels (for tiered achievements)
     */
    const LEVEL_BRONZE = 1;
    const LEVEL_SILVER = 2;
    const LEVEL_GOLD = 3;
    const LEVEL_PLATINUM = 4;

    /**
     * Get the users who have earned this achievement.
     */
    public function earnedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    /**
     * Check if a user meets the requirements for this achievement.
     * 
     * @param User $user
     * @return bool
     */
    public function checkRequirements(User $user): bool
    {
        // Different logic based on achievement category
        switch ($this->category) {
            case self::CATEGORY_STREAK:
                return $this->checkStreakRequirements($user);
            
            case self::CATEGORY_LESSON:
                return $this->checkLessonRequirements($user);
            
            case self::CATEGORY_SKILL:
                return $this->checkSkillRequirements($user);
            
            case self::CATEGORY_MODULE:
                return $this->checkModuleRequirements($user);
            
            case self::CATEGORY_PERFECTION:
                return $this->checkPerfectionRequirements($user);
            
            case self::CATEGORY_SPECIAL:
                return $this->checkSpecialRequirements($user);
            
            default:
                return false;
        }
    }

    /**
     * Check streak-related requirements.
     * 
     * @param User $user
     * @return bool
     */
    private function checkStreakRequirements(User $user): bool
    {
        $streak = $user->streak;
        if (!$streak) {
            return false;
        }

        $requiredDays = $this->requirements['streak_days'] ?? 0;
        return $streak->current_count >= $requiredDays;
    }

    /**
     * Check lesson completion requirements.
     * 
     * @param User $user
     * @return bool
     */
    private function checkLessonRequirements(User $user): bool
    {
        $completedCount = $user->completedLessons()->count();
        $requiredCount = $this->requirements['lesson_count'] ?? 0;
        
        return $completedCount >= $requiredCount;
    }

    /**
     * Check skill completion requirements.
     * 
     * @param User $user
     * @return bool
     */
    private function checkSkillRequirements(User $user): bool
    {
        $skillIds = $this->requirements['skill_ids'] ?? [];
        if (empty($skillIds)) {
            return false;
        }

        // Check if all specified skills are completed
        foreach ($skillIds as $skillId) {
            $progress = UserProgress::where('user_id', $user->id)
                ->where('skill_id', $skillId)
                ->where('completion_percentage', 100)
                ->exists();
            
            if (!$progress) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check module completion requirements.
     * 
     * @param User $user
     * @return bool
     */
    private function checkModuleRequirements(User $user): bool
    {
        $moduleIds = $this->requirements['module_ids'] ?? [];
        if (empty($moduleIds)) {
            return false;
        }

        // Check if all specified modules have 100% completion
        foreach ($moduleIds as $moduleId) {
            $module = Module::find($moduleId);
            if (!$module) {
                continue;
            }

            $completionPercentage = $module->getCompletionPercentage($user->id);
            if ($completionPercentage < 100) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check perfection requirements (perfect scores).
     * 
     * @param User $user
     * @return bool
     */
    private function checkPerfectionRequirements(User $user): bool
    {
        $perfectLessonCount = $this->requirements['perfect_lesson_count'] ?? 0;
        
        // Count lessons completed with 100% score
        $perfectLessons = $user->completedLessons()
            ->wherePivot('score', 100)
            ->count();
        
        return $perfectLessons >= $perfectLessonCount;
    }

    /**
     * Check special requirements (custom logic).
     * 
     * @param User $user
     * @return bool
     */
    private function checkSpecialRequirements(User $user): bool
    {
        // This would contain custom logic for special achievements
        // For now, return false as this needs specific implementation
        return false;
    }
}
