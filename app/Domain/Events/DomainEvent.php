<?php

namespace App\Domain\Events;

interface DomainEvent
{
    /**
     * Get the event name.
     *
     * @return string
     */
    public function getEventName(): string;

    /**
     * Get the timestamp when the event occurred.
     *
     * @return \DateTime
     */
    public function getOccurredOn(): \DateTime;
}
