<?php

namespace App\Domain\Events;

class AchievementEarnedEvent extends AbstractDomainEvent
{
    /**
     * The user ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * The achievement ID.
     *
     * @var int
     */
    private int $achievementId;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param int $achievementId
     */
    public function __construct(int $userId, int $achievementId)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->achievementId = $achievementId;
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
     * Get the achievement ID.
     *
     * @return int
     */
    public function getAchievementId(): int
    {
        return $this->achievementId;
    }
}
