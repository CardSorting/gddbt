<?php

namespace App\Domain\Models;

class UserProgress extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'skill_id',
        'completion_percentage',
        'completed_lessons',
        'last_activity_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completion_percentage' => 'float',
        'completed_lessons' => 'array',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the user that owns this progress record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skill that this progress record is for.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Update the progress after a lesson completion.
     * 
     * @param int $lessonId
     * @return void
     */
    public function updateAfterLessonCompletion(int $lessonId): void
    {
        // Add the lesson to completed lessons if not already there
        $completedLessons = $this->completed_lessons ?? [];
        if (!in_array($lessonId, $completedLessons)) {
            $completedLessons[] = $lessonId;
            $this->completed_lessons = $completedLessons;
        }

        // Update the completion percentage
        $totalLessons = $this->skill->lessons()->count();
        if ($totalLessons > 0) {
            $this->completion_percentage = (count($completedLessons) / $totalLessons) * 100;
        } else {
            $this->completion_percentage = 0;
        }

        $this->last_activity_at = now();
        $this->save();

        // Fire event for skill completion if 100%
        if ($this->completion_percentage >= 100) {
            // Here we would dispatch a domain event for skill completion
            $this->registerDomainEvent(new SkillCompletedEvent($this->user_id, $this->skill_id));
        }
    }

    /**
     * Get the next recommended lesson for this skill based on user progress.
     * 
     * @return Lesson|null
     */
    public function getNextRecommendedLesson()
    {
        $completedLessons = $this->completed_lessons ?? [];
        
        // Get the first incomplete lesson in order
        return $this->skill->lessons()
            ->whereNotIn('id', $completedLessons)
            ->orderBy('order')
            ->first();
    }
}
