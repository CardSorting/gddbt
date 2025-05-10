<?php

namespace App\Domain\Models;

class Lesson extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'skill_id',
        'name',
        'slug',
        'description',
        'content',
        'order',
        'duration_minutes',
        'xp_reward',
        'is_active',
        'is_premium',
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
        'duration_minutes' => 'integer',
        'xp_reward' => 'integer',
    ];

    /**
     * Get the skill that owns the lesson.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Get the exercises for the lesson.
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class)->orderBy('order');
    }

    /**
     * Get the users who have completed this lesson.
     */
    public function completedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_lesson_completions')
                    ->withPivot('completed_at', 'score')
                    ->withTimestamps();
    }

    /**
     * Check if this lesson is unlocked for a specific user.
     * 
     * @param int $userId
     * @return bool
     */
    public function isUnlockedForUser(int $userId): bool
    {
        // Check if the skill is unlocked first
        if (!$this->skill->isUnlockedForUser($userId)) {
            return false;
        }

        // If this is the first lesson in the skill, it's unlocked
        if ($this->order === 1) {
            return true;
        }

        // Otherwise, check if the previous lesson was completed
        $previousLesson = Lesson::where('skill_id', $this->skill_id)
            ->where('order', $this->order - 1)
            ->first();

        if (!$previousLesson) {
            return true; // No previous lesson found, so this one is unlocked
        }

        return $previousLesson->completedByUsers()
            ->where('users.id', $userId)
            ->exists();
    }

    /**
     * Calculate the user's progress through this lesson.
     * 
     * @param int $userId
     * @return array With 'completed' boolean and 'percentage' float
     */
    public function getUserProgress(int $userId): array
    {
        $completion = $this->completedByUsers()
            ->where('users.id', $userId)
            ->first();

        if ($completion) {
            return [
                'completed' => true,
                'percentage' => 100,
                'score' => $completion->pivot->score,
            ];
        }

        // Calculate partial progress based on completed exercises
        $totalExercises = $this->exercises()->count();
        
        if ($totalExercises === 0) {
            return ['completed' => false, 'percentage' => 0, 'score' => 0];
        }
        
        $completedExercises = $this->exercises()
            ->whereHas('completedByUsers', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->count();

        $percentage = ($completedExercises / $totalExercises) * 100;
        
        return [
            'completed' => false,
            'percentage' => $percentage,
            'score' => 0,
        ];
    }
}
