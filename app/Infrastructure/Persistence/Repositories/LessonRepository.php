<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\Lesson;
use App\Domain\Repositories\LessonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LessonRepository implements LessonRepositoryInterface
{
    /**
     * Find a lesson by its primary key.
     *
     * @param int $id
     * @return Lesson|null
     */
    public function find(int $id): ?Lesson
    {
        return Lesson::find($id);
    }
    
    /**
     * Find multiple lessons by their primary keys.
     *
     * @param array $ids
     * @return array
     */
    public function findMany(array $ids): array
    {
        return Lesson::whereIn('id', $ids)->get()->all();
    }

    /**
     * Find a lesson by its slug.
     *
     * @param string $slug
     * @return Lesson|null
     */
    public function findBySlug(string $slug): ?Lesson
    {
        return Lesson::where('slug', $slug)->first();
    }

    /**
     * Get all lessons.
     *
     * @return array
     */
    public function all(): array
    {
        try {
            return Lesson::orderBy('skill_id')
                ->orderBy('order')
                ->get()
                ->all();
        } catch (\Exception $e) {
            \Log::error('Error retrieving all lessons: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all lessons for a specific skill.
     *
     * @param int $skillId
     * @param bool $activeOnly Whether to include only active lessons
     * @return array
     */
    public function getBySkill(int $skillId, bool $activeOnly = true): array
    {
        try {
            $query = Lesson::where('skill_id', $skillId);
            
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            
            return $query->orderBy('order')
                ->get()
                ->all();
        } catch (\Exception $e) {
            \Log::error('Error retrieving lessons by skill: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get lessons with their exercises.
     *
     * @param int $skillId
     * @param bool $activeOnly Whether to include only active lessons and exercises
     * @return array
     */
    public function getWithExercises(int $skillId, bool $activeOnly = true): array
    {
        try {
            $query = Lesson::with(['exercises' => function ($query) use ($activeOnly) {
                if ($activeOnly) {
                    $query->where('is_active', true);
                }
                $query->orderBy('order');
            }])
            ->where('skill_id', $skillId);
            
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            
            return $query->orderBy('order')
                ->get()
                ->all();
        } catch (\Exception $e) {
            \Log::error('Error retrieving lessons with exercises: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Save a lesson.
     *
     * @param Lesson $lesson
     * @return Lesson
     */
    public function save($lesson): Lesson
    {
        $lesson->save();
        return $lesson;
    }

    /**
     * Delete a lesson.
     *
     * @param Lesson $lesson
     * @return bool
     */
    public function delete($lesson): bool
    {
        return $lesson->delete();
    }

    /**
     * Get the next lesson in sequence after the given lesson ID.
     *
     * @param int $currentLessonId
     * @return Lesson|null
     */
    public function getNextLesson(int $currentLessonId): ?Lesson
    {
        try {
            $currentLesson = $this->find($currentLessonId);
            if (!$currentLesson) {
                return null;
            }

            // Try to find the next lesson in the same skill
            $nextLesson = Lesson::where('skill_id', $currentLesson->skill_id)
                ->where('order', '>', $currentLesson->order)
                ->where('is_active', true)
                ->orderBy('order')
                ->first();

            if ($nextLesson) {
                return $nextLesson;
            }

            // If no next lesson in the same skill, find the first lesson in the next skill
            $skillRepo = new SkillRepository();
            $nextSkill = $skillRepo->getNextSkill($currentLesson->skill_id);
            
            if (!$nextSkill) {
                return null;
            }

            return Lesson::where('skill_id', $nextSkill->id)
                ->where('is_active', true)
                ->orderBy('order')
                ->first();
        } catch (\Exception $e) {
            \Log::error('Error retrieving next lesson: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get lessons completed by a specific user.
     *
     * @param int $userId
     * @param int|null $skillId Optional filter by skill
     * @return array
     */
    public function getCompletedByUser(int $userId, ?int $skillId = null): array
    {
        try {
            $query = Lesson::join('user_lesson_completions', 'lessons.id', '=', 'user_lesson_completions.lesson_id')
                ->where('user_lesson_completions.user_id', $userId);
            
            if ($skillId) {
                $query->where('lessons.skill_id', $skillId);
            }
            
            return $query->select(
                    'lessons.*',
                    'user_lesson_completions.completed_at',
                    'user_lesson_completions.score'
                )
                ->orderBy('user_lesson_completions.completed_at', 'desc')
                ->get()
                ->all();
        } catch (\Exception $e) {
            \Log::error('Error retrieving completed lessons: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recommended next lessons for a user based on their progress.
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getRecommendedForUser(int $userId, int $limit = 3): array
    {
        try {
            // Get lessons from skills that are unlocked but not completed
            $subquery = DB::table('user_progress')
                ->where('user_id', $userId)
                ->where('completion_percentage', '<', 100)
                ->select('skill_id');
                
            $lessons = Lesson::whereIn('skill_id', $subquery)
                ->whereNotIn('id', function ($query) use ($userId) {
                    $query->select('lesson_id')
                        ->from('user_lesson_completions')
                        ->where('user_id', $userId);
                })
                ->where('is_active', true)
                ->limit($limit)
                ->get();
                
            // If we couldn't find enough lessons, get lessons from skills the user hasn't started yet
            if (count($lessons) < $limit) {
                $remainingCount = $limit - count($lessons);
                
                $completedSkills = DB::table('user_progress')
                    ->where('user_id', $userId)
                    ->select('skill_id');
                    
                $newLessons = Lesson::whereNotIn('skill_id', $completedSkills)
                    ->where('is_active', true)
                    ->whereIn('skill_id', function ($query) use ($userId) {
                        $query->select('id')
                            ->from('skills')
                            ->where('is_active', true)
                            ->whereRaw('(prerequisites IS NULL OR JSON_LENGTH(prerequisites) = 0)');
                    })
                    ->orderBy('skill_id')
                    ->orderBy('order')
                    ->limit($remainingCount)
                    ->get();
                    
                $lessons = $lessons->merge($newLessons);
            }
            
            return $lessons->all();
        } catch (\Exception $e) {
            \Log::error('Error retrieving recommended lessons: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Record a user's completion of a lesson.
     *
     * @param int $userId
     * @param int $lessonId
     * @param int $score
     * @param \DateTime|null $completedAt
     * @return bool
     */
    public function recordCompletion(int $userId, int $lessonId, int $score, $completedAt = null): bool
    {
        try {
            if ($completedAt === null) {
                $completedAt = now();
            }

            $completion = DB::table('user_lesson_completions')
                ->where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->first();

            if ($completion) {
                // If the lesson was already completed, update the record only if the new score is higher
                if ($score > $completion->score) {
                    DB::table('user_lesson_completions')
                        ->where('user_id', $userId)
                        ->where('lesson_id', $lessonId)
                        ->update([
                            'score' => $score,
                            'completed_at' => $completedAt,
                            'updated_at' => now(),
                        ]);
                }
            } else {
                // If the lesson wasn't completed before, create a new record
                DB::table('user_lesson_completions')
                    ->insert([
                        'user_id' => $userId,
                        'lesson_id' => $lessonId,
                        'score' => $score,
                        'completed_at' => $completedAt,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error recording lesson completion: ' . $e->getMessage());
            return false;
        }
    }
}
