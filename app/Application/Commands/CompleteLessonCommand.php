<?php

namespace App\Application\Commands;

class CompleteLessonCommand implements CommandInterface
{
    /**
     * User ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * Lesson ID.
     *
     * @var int
     */
    private int $lessonId;

    /**
     * Completion score (0-100).
     *
     * @var int
     */
    private int $score;

    /**
     * Create a new command instance.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $score
     */
    public function __construct(int $userId, int $lessonId, int $score)
    {
        $this->userId = $userId;
        $this->lessonId = $lessonId;
        $this->score = $score;
    }

    /**
     * Get the command type.
     *
     * @return string
     */
    public function getCommandType(): string
    {
        return 'complete_lesson';
    }

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Get the lesson ID.
     *
     * @return int
     */
    public function getLessonId(): int
    {
        return $this->lessonId;
    }

    /**
     * Get the score.
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
