<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Module;

interface ModuleRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a module by its slug.
     *
     * @param string $slug
     * @return Module|null
     */
    public function findBySlug(string $slug): ?Module;

    /**
     * Get all active modules ordered by their display order.
     *
     * @return array
     */
    public function getAllActive(): array;

    /**
     * Get modules with their skills.
     *
     * @param bool $activeOnly Whether to include only active modules
     * @return array
     */
    public function getAllWithSkills(bool $activeOnly = true): array;

    /**
     * Get the next module in sequence after the given module ID.
     *
     * @param int $currentModuleId
     * @return Module|null
     */
    public function getNextModule(int $currentModuleId): ?Module;

    /**
     * Get modules with completion statistics for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getModulesWithUserProgress(int $userId): array;
}
