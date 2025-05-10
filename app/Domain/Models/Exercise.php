<?php

namespace App\Domain\Models;

class Exercise extends Entity
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'type',
        'content',
        'options',
        'correct_answer',
        'order',
        'difficulty',
        'xp_reward',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'difficulty' => 'integer',
        'xp_reward' => 'integer',
        'options' => 'array',
        'correct_answer' => 'array',
    ];

    /**
     * Exercise types
     */
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_TEXT_INPUT = 'text_input';
    const TYPE_DRAG_DROP = 'drag_drop';
    const TYPE_MATCHING = 'matching';
    const TYPE_REFLECTION = 'reflection';
    const TYPE_SCENARIO = 'scenario';
    const TYPE_PRACTICE = 'practice';

    /**
     * Get the lesson that owns the exercise.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the users who have completed this exercise.
     */
    public function completedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_exercise_completions')
                    ->withPivot('completed_at', 'answer', 'is_correct', 'points_earned')
                    ->withTimestamps();
    }

    /**
     * Check if the provided answer is correct for this exercise.
     * 
     * @param mixed $answer The user's answer
     * @return bool
     */
    public function checkAnswer($answer): bool
    {
        switch ($this->type) {
            case self::TYPE_MULTIPLE_CHOICE:
                return $this->checkMultipleChoiceAnswer($answer);
            
            case self::TYPE_TEXT_INPUT:
                return $this->checkTextInputAnswer($answer);
            
            case self::TYPE_DRAG_DROP:
            case self::TYPE_MATCHING:
                return $this->checkOrderedAnswer($answer);
            
            case self::TYPE_REFLECTION:
            case self::TYPE_SCENARIO:
            case self::TYPE_PRACTICE:
                // These types don't have correct/incorrect answers
                return true;
            
            default:
                return false;
        }
    }

    /**
     * Check a multiple choice answer.
     * 
     * @param mixed $answer
     * @return bool
     */
    private function checkMultipleChoiceAnswer($answer): bool
    {
        // For multiple choice, compare selected option(s) with correct answer(s)
        if (!is_array($answer)) {
            $answer = [$answer];
        }
        
        sort($answer);
        $correctAnswer = $this->correct_answer;
        sort($correctAnswer);
        
        return $answer == $correctAnswer;
    }

    /**
     * Check a text input answer.
     * 
     * @param string $answer
     * @return bool
     */
    private function checkTextInputAnswer(string $answer): bool
    {
        // For text input, check if answer matches any of the correct answers
        // (case insensitive)
        $answer = strtolower(trim($answer));
        foreach ($this->correct_answer as $correctAnswer) {
            if (strtolower(trim($correctAnswer)) === $answer) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check an ordered answer (drag & drop, matching).
     * 
     * @param array $answer
     * @return bool
     */
    private function checkOrderedAnswer(array $answer): bool
    {
        // For drag & drop and matching, compare the ordered items
        return $answer == $this->correct_answer;
    }
}
