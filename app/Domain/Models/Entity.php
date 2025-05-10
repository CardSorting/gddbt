<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Entity extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Get the domain events for the entity.
     *
     * @return array
     */
    public function getDomainEvents(): array
    {
        return $this->domainEvents ?? [];
    }

    /**
     * Clear the domain events.
     *
     * @return void
     */
    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }

    /**
     * Register a domain event for this entity.
     *
     * @param mixed $event
     * @return void
     */
    protected function registerDomainEvent($event): void
    {
        $this->domainEvents[] = $event;
    }
}
