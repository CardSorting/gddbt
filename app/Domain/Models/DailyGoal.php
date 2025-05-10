<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyGoal extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'date',
        'skills_used',
        'daily_goal',
        'tomorrow_goal',
        'highlight',
        'gratitude',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'skills_used' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * Get the user that owns the daily goal.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skills referenced in this daily goal.
     *
     * @return array
     */
    public function getSkills()
    {
        if (empty($this->skills_used)) {
            return [];
        }

        return Skill::whereIn('id', $this->skills_used)->get();
    }

    /**
     * Determine if the daily goal is visible to a given user.
     *
     * @param User|null $viewer
     * @return bool
     */
    public function isVisibleTo(?User $viewer): bool
    {
        // Always visible to the owner
        if ($viewer && $viewer->id === $this->user_id) {
            return true;
        }

        // Public goals are visible to everyone
        if ($this->is_public) {
            // Check if the owner's profile allows sharing goals
            return $this->user->share_daily_goals;
        }

        // Private goals are only visible to the owner
        return false;
    }

    /**
     * Toggle the visibility of this daily goal.
     *
     * @return bool
     */
    public function toggleVisibility(): bool
    {
        $this->is_public = !$this->is_public;
        return $this->save();
    }
}
