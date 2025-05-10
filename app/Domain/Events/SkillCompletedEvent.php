<?php

namespace App\Domain\Events;

class SkillCompletedEvent extends AbstractDomainEvent
{
    /**
     * The user ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * The skill ID.
     *
     * @var int
     */
    private int $skillId;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param int $skillId
     */
    public function __construct(int $userId, int $skillId)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->skillId = $skillId;
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
     * Get the skill ID.
     *
     * @return int
     */
    public function getSkillId(): int
    {
        return $this->skillId;
    }
}
