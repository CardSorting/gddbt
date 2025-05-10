<?php

namespace App\Domain\Repositories;

interface RepositoryInterface
{
    /**
     * Find an entity by its primary key.
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Find multiple entities by their primary keys.
     *
     * @param array $ids
     * @return array
     */
    public function findMany(array $ids): array;

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
     * @return mixed
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
