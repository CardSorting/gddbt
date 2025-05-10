<?php

namespace App\Domain\Models;

class Streak extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'current_count',
        'longest_count',
        'last_activity_date',
        'freeze_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'current_count' => 'integer',
        'longest_count' => 'integer',
        'last_activity_date' => 'date',
        'freeze_count' => 'integer',
    ];

    /**
     * Get the user that owns this streak.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the streak is active for today.
     * 
     * @return bool
     */
    public function isActiveToday(): bool
    {
        if (!$this->last_activity_date) {
            return false;
        }
        
        return $this->last_activity_date->isToday();
    }

    /**
     * Update the streak based on user activity.
     * 
     * @return array Result containing new streak count and if streak increased
     */
    public function updateStreak(): array
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();
        $oldCount = $this->current_count;
        
        // If first time or no previous activity
        if (!$this->last_activity_date) {
            $this->current_count = 1;
            $this->longest_count = 1;
            $this->last_activity_date = $today;
            $this->save();
            
            return [
                'new_count' => 1,
                'increased' => true,
                'milestone' => true,
            ];
        }
        
        // Already active today
        if ($this->last_activity_date->isSameDay($today)) {
            return [
                'new_count' => $this->current_count,
                'increased' => false,
                'milestone' => false,
            ];
        }
        
        // If active yesterday, increment streak
        if ($this->last_activity_date->isSameDay($yesterday)) {
            $this->current_count++;
            if ($this->current_count > $this->longest_count) {
                $this->longest_count = $this->current_count;
            }
            $this->last_activity_date = $today;
            $this->save();
            
            // Check if reached a milestone (multiples of 7)
            $milestone = $this->current_count > 0 && $this->current_count % 7 === 0;
            
            return [
                'new_count' => $this->current_count,
                'increased' => true,
                'milestone' => $milestone,
            ];
        }
        
        // Missed days but have freezes available
        if ($this->freeze_count > 0) {
            $daysMissed = $this->last_activity_date->diffInDays($today) - 1;
            
            if ($daysMissed <= $this->freeze_count) {
                // Use freezes to maintain streak
                $this->freeze_count -= $daysMissed;
                $this->current_count++;
                if ($this->current_count > $this->longest_count) {
                    $this->longest_count = $this->current_count;
                }
                $this->last_activity_date = $today;
                $this->save();
                
                // Check if reached a milestone (multiples of 7)
                $milestone = $this->current_count > 0 && $this->current_count % 7 === 0;
                
                return [
                    'new_count' => $this->current_count,
                    'increased' => true,
                    'milestone' => $milestone,
                    'freezes_used' => $daysMissed,
                ];
            }
        }
        
        // Gap too large, reset streak
        $this->current_count = 1;
        $this->last_activity_date = $today;
        $this->save();
        
        return [
            'new_count' => 1,
            'increased' => false,
            'milestone' => false,
            'streak_reset' => true,
            'previous_streak' => $oldCount,
        ];
    }

    /**
     * Add streak freeze protection.
     * 
     * @param int $count Number of freeze days to add
     * @return int New freeze count
     */
    public function addFreezeProtection(int $count): int
    {
        $this->freeze_count += $count;
        $this->save();
        
        return $this->freeze_count;
    }
}
