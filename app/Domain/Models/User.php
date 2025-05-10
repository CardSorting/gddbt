<?php

namespace App\Domain\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'xp_points',
        'level',
        'last_login_at',
        'private_profile',
        'share_streaks',
        'share_progress',
        'share_daily_goals',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'private_profile' => 'boolean',
        'share_streaks' => 'boolean',
        'share_progress' => 'boolean',
        'share_daily_goals' => 'boolean',
    ];

    /**
     * Get the user's current streak.
     */
    public function streak()
    {
        return $this->hasOne(Streak::class);
    }

    /**
     * Get the achievements earned by the user.
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    /**
     * Get the user's progress on skills.
     */
    public function skillProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get the user's completed lessons.
     */
    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'user_lesson_completions')
                    ->withPivot('completed_at', 'score')
                    ->withTimestamps();
    }

    /**
     * Check if the user is eligible for any achievements.
     * 
     * @return void
     */
    public function checkForAchievements(): void
    {
        // This will be implemented to check and award achievements
    }

    /**
     * Award XP points to the user and check for level up.
     * 
     * @param int $points
     * @return bool Whether the user leveled up
     */
    public function awardXp(int $points): bool
    {
        $oldLevel = $this->level;
        $this->xp_points += $points;
        
        // Calculate new level (simplified formula)
        $this->level = floor(sqrt($this->xp_points / 100));
        
        $this->save();
        
        return $oldLevel < $this->level;
    }

    /**
     * Get all users that this user is following.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Get all users following this user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Get the user's daily goals.
     */
    public function dailyGoals()
    {
        return $this->hasMany(DailyGoal::class);
    }

    /**
     * Follow another user.
     * 
     * @param User $user
     * @return bool
     */
    public function follow(User $user): bool
    {
        // Can't follow yourself
        if ($this->id === $user->id) {
            return false;
        }

        // If already following, return true
        if ($this->following()->where('user_id', $user->id)->exists()) {
            return true;
        }

        $this->following()->attach($user->id);
        return true;
    }

    /**
     * Unfollow another user.
     * 
     * @param User $user
     * @return bool
     */
    public function unfollow(User $user): bool
    {
        return (bool) $this->following()->detach($user->id);
    }

    /**
     * Check if the user is following another user.
     * 
     * @param User $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine if the user's profile is visible to another user.
     * 
     * @param User|null $viewer
     * @return bool
     */
    public function isVisibleTo(?User $viewer): bool
    {
        // Always visible to self
        if ($viewer && $viewer->id === $this->id) {
            return true;
        }

        // If profile is private, only visible to followers
        if ($this->private_profile) {
            return $viewer && $this->followers()->where('follower_id', $viewer->id)->exists();
        }

        // Public profile is visible to everyone
        return true;
    }

    /**
     * Get today's daily goal for the user.
     * 
     * @return DailyGoal|null
     */
    public function getTodayGoal()
    {
        return $this->dailyGoals()
                    ->whereDate('date', now()->toDateString())
                    ->first();
    }
}
