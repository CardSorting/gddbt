<?php

namespace App\Domain\Events;

class StreakUpdatedEvent extends AbstractDomainEvent
{
    /**
     * The user ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * The current streak count.
     *
     * @var int
     */
    private int $streakCount;

    /**
     * Whether the streak increased.
     *
     * @var bool
     */
    private bool $increased;

    /**
     * Whether a streak milestone was reached.
     *
     * @var bool
     */
    private bool $milestone;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param int $streakCount
     * @param bool $increased
     * @param bool $milestone
     */
    public function __construct(int $userId, int $streakCount, bool $increased, bool $milestone = false)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->streakCount = $streakCount;
        $this->increased = $increased;
        $this->milestone = $milestone;
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
     * Get the streak count.
     *
     * @return int
     */
    public function getStreakCount(): int
    {
        return $this->streakCount;
    }

    /**
     * Check if the streak increased.
     *
     * @return bool
     */
    public function hasIncreased(): bool
    {
        return $this->increased;
    }

    /**
     * Check if a milestone was reached.
     *
     * @return bool
     */
    public function isMilestone(): bool
    {
        return $this->milestone;
    }
}
