<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Exercise;

interface ExerciseRepositoryInterface extends RepositoryInterface
{
    /**
     * Get all exercises for a specific lesson.
     *
     * @param int $lessonId
     * @param bool $activeOnly Whether to include only active exercises
     * @return array
     */
    public function getByLesson(int $lessonId, bool $activeOnly = true): array;

    /**
     * Get exercises by type.
     *
     * @param string $type
     * @param int|null $lessonId Optional filter by lesson
     * @return array
     */
    public function getByType(string $type, ?int $lessonId = null): array;

    /**
     * Get exercises completed by a specific user.
     *
     * @param int $userId
     * @param int|null $lessonId Optional filter by lesson
     * @return array
     */
    public function getCompletedByUser(int $userId, ?int $lessonId = null): array;

    /**
     * Get exercises with completion statistics for a specific user.
     *
     * @param int $userId
     * @param int $lessonId
     * @return array
     */
    public function getWithUserProgress(int $userId, int $lessonId): array;

    /**
     * Get exercises by difficulty level.
     *
     * @param int $difficulty
     * @param int|null $limit
     * @return array
     */
    public function getByDifficulty(int $difficulty, ?int $limit = null): array;

    /**
     * Record a user's completion of an exercise.
     *
     * @param int $userId
     * @param int $exerciseId
     * @param mixed $answer
     * @param bool $isCorrect
     * @param int $pointsEarned
     * @return bool
     */
    public function recordCompletion(int $userId, int $exerciseId, $answer, bool $isCorrect, int $pointsEarned): bool;
}
