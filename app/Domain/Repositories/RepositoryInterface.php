<?php

namespace App\Domain\Repositories;

interface RepositoryInterface
{
    /**
     * Find an entity by its primary key.
     *
     * @param int $id
     * @return mixed The entity or null if not found
     */
    public function find(int $id);

    /**
     * Get all entities.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Save an entity.
     *
     * @param mixed $entity
     * @return mixed The saved entity
     */
    public function save($entity);

    /**
     * Delete an entity.
     *
     * @param mixed $entity
     * @return bool
     */
    public function delete($entity): bool;
}
