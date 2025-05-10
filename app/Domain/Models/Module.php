<?php

namespace App\Domain\Models;

class Module extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modules';

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
        'order',
        'is_active',
        'color_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the skills that belong to the module.
     */
    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }

    /**
     * Get the lessons that belong to the module.
     */
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Skill::class)->orderBy('lessons.order');
    }

    /**
     * Get the completion percentage for this module for a specific user.
     * 
     * @param int $userId
     * @return float
     */
    public function getCompletionPercentage(int $userId): float
    {
        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->lessons()
            ->whereHas('completedByUsers', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->count();

        return ($completedLessons / $totalLessons) * 100;
    }

    /**
     * Get the total XP available in this module.
     * 
     * @return int
     */
    public function getTotalXp(): int
    {
        return $this->lessons()->sum('xp_reward');
    }
}
