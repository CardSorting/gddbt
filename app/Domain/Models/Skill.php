<?php

namespace App\Domain\Models;

class Skill extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
        'is_premium',
        'prerequisites',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'order' => 'integer',
        'prerequisites' => 'array',
    ];

    /**
     * Get the module that owns the skill.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the lessons for the skill.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Get the progress records for this skill.
     */
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Check if this skill is unlocked for a specific user.
     * 
     * @param int $userId
     * @return bool
     */
    public function isUnlockedForUser(int $userId): bool
    {
        // If no prerequisites, skill is always unlocked
        if (empty($this->prerequisites)) {
            return true;
        }

        // Check if all prerequisite skills are completed
        foreach ($this->prerequisites as $prerequisiteId) {
            $prerequisiteCompleted = UserProgress::where('user_id', $userId)
                ->where('skill_id', $prerequisiteId)
                ->where('completion_percentage', 100)
                ->exists();
            
            if (!$prerequisiteCompleted) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the completion percentage for this skill for a specific user.
     * 
     * @param int $userId
     * @return float
     */
    public function getCompletionPercentage(int $userId): float
    {
        $progress = $this->userProgress()
            ->where('user_id', $userId)
            ->first();

        return $progress ? $progress->completion_percentage : 0;
    }

    /**
     * Get the total XP available in this skill.
     * 
     * @return int
     */
    public function getTotalXp(): int
    {
        return $this->lessons()->sum('xp_reward');
    }
}
