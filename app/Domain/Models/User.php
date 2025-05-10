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
}
