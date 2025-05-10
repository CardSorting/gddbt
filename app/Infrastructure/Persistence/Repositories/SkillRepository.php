<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\Skill;
use App\Domain\Repositories\SkillRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class SkillRepository implements SkillRepositoryInterface
{
    /**
     * Find a skill by its primary key.
     *
     * @param int $id
     * @return Skill|null
     */
    public function find(int $id): ?Skill
    {
        return Skill::find($id);
    }

    /**
     * Find a skill by its slug.
     *
     * @param string $slug
     * @return Skill|null
     */
    public function findBySlug(string $slug): ?Skill
    {
        return Skill::where('slug', $slug)->first();
    }

    /**
     * Get all skills.
     *
     * @return array
     */
    public function all(): array
    {
        return Skill::orderBy('module_id')
            ->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Get all skills for a specific module.
     *
     * @param int $moduleId
     * @param bool $activeOnly Whether to include only active skills
     * @return array
     */
    public function getByModule(int $moduleId, bool $activeOnly = true): array
    {
        $query = Skill::where('module_id', $moduleId);
        
        if ($activeOnly) {
            $query->where('is_active', true);
        }
        
        return $query->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Get skills with their lessons.
     *
     * @param int $moduleId
     * @param bool $activeOnly Whether to include only active skills and lessons
     * @return array
     */
    public function getWithLessons(int $moduleId, bool $activeOnly = true): array
    {
        $query = Skill::with(['lessons' => function ($query) use ($activeOnly) {
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            $query->orderBy('order');
        }])
        ->where('module_id', $moduleId);
        
        if ($activeOnly) {
            $query->where('is_active', true);
        }
        
        return $query->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Save a skill.
     *
     * @param Skill $skill
     * @return Skill
     */
    public function save($skill): Skill
    {
        $skill->save();
        return $skill;
    }

    /**
     * Delete a skill.
     *
     * @param Skill $skill
     * @return bool
     */
    public function delete($skill): bool
    {
        return $skill->delete();
    }

    /**
     * Get the next skill in sequence after the given skill ID.
     *
     * @param int $currentSkillId
     * @return Skill|null
     */
    public function getNextSkill(int $currentSkillId): ?Skill
    {
        $currentSkill = $this->find($currentSkillId);
        if (!$currentSkill) {
            return null;
        }

        // Try to find the next skill in the same module
        $nextSkill = Skill::where('module_id', $currentSkill->module_id)
            ->where('order', '>', $currentSkill->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();

        if ($nextSkill) {
            return $nextSkill;
        }

        // If no next skill in the same module, find the first skill in the next module
        $moduleRepo = new ModuleRepository();
        $nextModule = $moduleRepo->getNextModule($currentSkill->module_id);
        
        if (!$nextModule) {
            return null;
        }

        return Skill::where('module_id', $nextModule->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }

    /**
     * Get skills that have specific prerequisites.
     *
     * @param array $skillIds
     * @return array
     */
    public function getSkillsWithPrerequisites(array $skillIds): array
    {
        return Skill::where(function ($query) use ($skillIds) {
            foreach ($skillIds as $skillId) {
                $query->orWhereJsonContains('prerequisites', $skillId);
            }
        })
        ->get()
        ->all();
    }

    /**
     * Get skills with completion statistics for a specific user.
     *
     * @param int $userId
     * @param int|null $moduleId Optional filter by module
     * @return array
     */
    public function getSkillsWithUserProgress(int $userId, ?int $moduleId = null): array
    {
        $query = Skill::leftJoin('user_progress', function ($join) use ($userId) {
            $join->on('skills.id', '=', 'user_progress.skill_id')
                ->where('user_progress.user_id', '=', $userId);
        })
        ->select(
            'skills.*',
            'user_progress.completion_percentage',
            'user_progress.last_activity_at'
        );
        
        if ($moduleId) {
            $query->where('module_id', $moduleId);
        }
        
        $skills = $query->where('skills.is_active', true)
            ->orderBy('skills.module_id')
            ->orderBy('skills.order')
            ->get();
        
        return $skills->map(function ($skill) use ($userId) {
            $isUnlocked = $skill->isUnlockedForUser($userId);
            
            return [
                'id' => $skill->id,
                'module_id' => $skill->module_id,
                'name' => $skill->name,
                'slug' => $skill->slug,
                'description' => $skill->description,
                'icon' => $skill->icon,
                'order' => $skill->order,
                'is_premium' => $skill->is_premium,
                'prerequisites' => $skill->prerequisites,
                'progress' => $skill->completion_percentage ?? 0,
                'is_unlocked' => $isUnlocked,
                'last_activity_at' => $skill->last_activity_at,
            ];
        })
        ->all();
    }
}
