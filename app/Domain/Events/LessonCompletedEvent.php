<?php

namespace App\Domain\Events;

class LessonCompletedEvent extends AbstractDomainEvent
{
    /**
     * The user ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * The lesson ID.
     *
     * @var int
     */
    private int $lessonId;

    /**
     * The score (percentage).
     *
     * @var int
     */
    private int $score;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $score
     */
    public function __construct(int $userId, int $lessonId, int $score)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->lessonId = $lessonId;
        $this->score = $score;
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
