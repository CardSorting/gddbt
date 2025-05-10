<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\Exercise;
use App\Domain\Repositories\ExerciseRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ExerciseRepository implements ExerciseRepositoryInterface
{
    /**
     * Find an exercise by its primary key.
     *
     * @param int $id
     * @return Exercise|null
     */
    public function find(int $id): ?Exercise
    {
        return Exercise::find($id);
    }

    /**
     * Get all exercises.
     *
     * @return array
     */
    public function all(): array
    {
        return Exercise::orderBy('lesson_id')
            ->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Get all exercises for a specific lesson.
     *
     * @param int $lessonId
     * @param bool $activeOnly Whether to include only active exercises
     * @return array
     */
    public function getByLesson(int $lessonId, bool $activeOnly = true): array
    {
        $query = Exercise::where('lesson_id', $lessonId);
        
        if ($activeOnly) {
            $query->where('is_active', true);
        }
        
        return $query->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Get exercises by type.
     *
     * @param string $type
     * @param int|null $lessonId Optional filter by lesson
     * @return array
     */
    public function getByType(string $type, ?int $lessonId = null): array
    {
        $query = Exercise::where('type', $type);
        
        if ($lessonId) {
            $query->where('lesson_id', $lessonId);
        }
        
        return $query->orderBy('lesson_id')
            ->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Save an exercise.
     *
     * @param Exercise $exercise
     * @return Exercise
     */
    public function save($exercise): Exercise
    {
        $exercise->save();
        return $exercise;
    }

    /**
     * Delete an exercise.
     *
     * @param Exercise $exercise
     * @return bool
     */
    public function delete($exercise): bool
    {
        return $exercise->delete();
    }

    /**
     * Get exercises completed by a specific user.
     *
     * @param int $userId
     * @param int|null $lessonId Optional filter by lesson
     * @return array
     */
    public function getCompletedByUser(int $userId, ?int $lessonId = null): array
    {
        $query = Exercise::join('user_exercise_completions', 'exercises.id', '=', 'user_exercise_completions.exercise_id')
            ->where('user_exercise_completions.user_id', $userId)
            ->where('user_exercise_completions.is_correct', true);
        
        if ($lessonId) {
            $query->where('exercises.lesson_id', $lessonId);
        }
        
        return $query->select(
                'exercises.*',
                'user_exercise_completions.completed_at',
                'user_exercise_completions.points_earned'
            )
            ->orderBy('user_exercise_completions.completed_at', 'desc')
            ->get()
            ->all();
    }

    /**
     * Get exercises with completion statistics for a specific user.
     *
     * @param int $userId
     * @param int $lessonId
     * @return array
     */
    public function getWithUserProgress(int $userId, int $lessonId): array
    {
        return Exercise::where('lesson_id', $lessonId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($exercise) use ($userId) {
                $completion = DB::table('user_exercise_completions')
                    ->where('user_id', $userId)
                    ->where('exercise_id', $exercise->id)
                    ->orderBy('completed_at', 'desc')
                    ->first();
                
                return [
                    'id' => $exercise->id,
                    'title' => $exercise->title,
                    'type' => $exercise->type,
                    'lesson_id' => $exercise->lesson_id,
                    'order' => $exercise->order,
                    'difficulty' => $exercise->difficulty,
                    'xp_reward' => $exercise->xp_reward,
                    'completed' => $completion ? true : false,
                    'is_correct' => $completion ? $completion->is_correct : null,
                    'points_earned' => $completion ? $completion->points_earned : 0,
                    'completed_at' => $completion ? $completion->completed_at : null,
                    'attempts' => $completion ? $completion->attempts : 0,
                ];
            })
            ->all();
    }

    /**
     * Get exercises by difficulty level.
     *
     * @param int $difficulty
     * @param int|null $limit
     * @return array
     */
    public function getByDifficulty(int $difficulty, ?int $limit = null): array
    {
        $query = Exercise::where('difficulty', $difficulty)
            ->where('is_active', true)
            ->orderBy('lesson_id')
            ->orderBy('order');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get()->all();
    }

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
    public function recordCompletion(int $userId, int $exerciseId, $answer, bool $isCorrect, int $pointsEarned): bool
    {
        $exercise = $this->find($exerciseId);
        if (!$exercise) {
            return false;
        }

        // Check if user has attempted this exercise before
        $previousAttempt = DB::table('user_exercise_completions')
            ->where('user_id', $userId)
            ->where('exercise_id', $exerciseId)
            ->first();

        $attempts = $previousAttempt ? $previousAttempt->attempts + 1 : 1;
        
        // Serialize answer if it's an array or object
        $serializedAnswer = is_array($answer) || is_object($answer) 
            ? json_encode($answer) 
            : $answer;

        if ($previousAttempt) {
            // Update existing record
            DB::table('user_exercise_completions')
                ->where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->update([
                    'answer' => $serializedAnswer,
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $pointsEarned : $previousAttempt->points_earned,
                    'attempts' => $attempts,
                    'completed_at' => now(),
                    'updated_at' => now(),
                ]);
        } else {
            // Create new record
            DB::table('user_exercise_completions')
                ->insert([
                    'user_id' => $userId,
                    'exercise_id' => $exerciseId,
                    'answer' => $serializedAnswer,
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $pointsEarned : 0,
                    'attempts' => $attempts,
                    'completed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        return true;
    }
}
