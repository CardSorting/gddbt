<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Lesson;

interface LessonRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a lesson by its slug.
     *
     * @param string $slug
     * @return Lesson|null
     */
    public function findBySlug(string $slug): ?Lesson;

    /**
     * Get all lessons for a specific skill.
     *
     * @param int $skillId
     * @param bool $activeOnly Whether to include only active lessons
     * @return array
     */
    public function getBySkill(int $skillId, bool $activeOnly = true): array;

    /**
     * Get lessons with their exercises.
     *
     * @param int $skillId
     * @param bool $activeOnly Whether to include only active lessons and exercises
     * @return array
     */
    public function getWithExercises(int $skillId, bool $activeOnly = true): array;

    /**
     * Get the next lesson in sequence after the given lesson ID.
     *
     * @param int $currentLessonId
     * @return Lesson|null
     */
    public function getNextLesson(int $currentLessonId): ?Lesson;

    /**
     * Get lessons completed by a specific user.
     *
     * @param int $userId
     * @param int|null $skillId Optional filter by skill
     * @return array
     */
    public function getCompletedByUser(int $userId, ?int $skillId = null): array;

    /**
     * Get recommended next lessons for a user based on their progress.
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getRecommendedForUser(int $userId, int $limit = 3): array;
}
