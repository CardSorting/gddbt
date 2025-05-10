<?php

namespace App\Domain\Events;

abstract class AbstractDomainEvent implements DomainEvent
{
    /**
     * The timestamp when the event occurred.
     *
     * @var \DateTime
     */
    private \DateTime $occurredOn;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->occurredOn = new \DateTime();
    }

    /**
     * Get the timestamp when the event occurred.
     *
     * @return \DateTime
     */
    public function getOccurredOn(): \DateTime
    {
        return $this->occurredOn;
    }

    /**
     * Get the event name.
     *
     * @return string
     */
    public function getEventName(): string
    {
        return static::class;
    }
}
