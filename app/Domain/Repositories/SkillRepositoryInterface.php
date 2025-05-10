<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Skill;

interface SkillRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a skill by its slug.
     *
     * @param string $slug
     * @return Skill|null
     */
    public function findBySlug(string $slug): ?Skill;

    /**
     * Get all skills for a specific module.
     *
     * @param int $moduleId
     * @param bool $activeOnly Whether to include only active skills
     * @return array
     */
    public function getByModule(int $moduleId, bool $activeOnly = true): array;

    /**
     * Get skills with their lessons.
     *
     * @param int $moduleId
     * @param bool $activeOnly Whether to include only active skills and lessons
     * @return array
     */
    public function getWithLessons(int $moduleId, bool $activeOnly = true): array;

    /**
     * Get the next skill in sequence after the given skill ID.
     *
     * @param int $currentSkillId
     * @return Skill|null
     */
    public function getNextSkill(int $currentSkillId): ?Skill;

    /**
     * Get skills that have specific prerequisites.
     *
     * @param array $skillIds
     * @return array
     */
    public function getSkillsWithPrerequisites(array $skillIds): array;

    /**
     * Get skills with completion statistics for a specific user.
     *
     * @param int $userId
     * @param int|null $moduleId Optional filter by module
     * @return array
     */
    public function getSkillsWithUserProgress(int $userId, ?int $moduleId = null): array;
}
