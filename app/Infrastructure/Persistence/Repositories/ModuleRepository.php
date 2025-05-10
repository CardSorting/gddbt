<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\Module;
use App\Domain\Repositories\ModuleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ModuleRepository implements ModuleRepositoryInterface
{
    /**
     * Find a module by its primary key.
     *
     * @param int $id
     * @return Module|null
     */
    public function find(int $id): ?Module
    {
        return Module::find($id);
    }
    
    /**
     * Find multiple modules by their primary keys.
     *
     * @param array $ids
     * @return array
     */
    public function findMany(array $ids): array
    {
        return Module::whereIn('id', $ids)->get()->all();
    }

    /**
     * Find a module by its slug.
     *
     * @param string $slug
     * @return Module|null
     */
    public function findBySlug(string $slug): ?Module
    {
        return Module::where('slug', $slug)->first();
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all(): array
    {
        return Module::orderBy('order')->get()->all();
    }

    /**
     * Get all active modules ordered by their display order.
     *
     * @return array
     */
    public function getAllActive(): array
    {
        return Module::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->all();
    }

    /**
     * Get modules with their skills.
     *
     * @param bool $activeOnly Whether to include only active modules
     * @return array
     */
    public function getAllWithSkills(bool $activeOnly = true): array
    {
        $query = Module::with(['skills' => function ($query) use ($activeOnly) {
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            $query->orderBy('order');
        }]);

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->orderBy('order')->get()->all();
    }

    /**
     * Save a module.
     *
     * @param Module $module
     * @return Module
     */
    public function save($module): Module
    {
        $module->save();
        return $module;
    }

    /**
     * Delete a module.
     *
     * @param Module $module
     * @return bool
     */
    public function delete($module): bool
    {
        return $module->delete();
    }

    /**
     * Get the next module in sequence after the given module ID.
     *
     * @param int $currentModuleId
     * @return Module|null
     */
    public function getNextModule(int $currentModuleId): ?Module
    {
        $currentModule = $this->find($currentModuleId);
        if (!$currentModule) {
            return null;
        }

        return Module::where('order', '>', $currentModule->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }

    /**
     * Get modules with completion statistics for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getModulesWithUserProgress(int $userId): array
    {
        $modules = $this->getAllActive();
        
        // Enhance each module with progress information
        return array_map(function ($module) use ($userId) {
            $completionPercentage = $module->getCompletionPercentage($userId);
            
            return [
                'id' => $module->id,
                'name' => $module->name,
                'slug' => $module->slug,
                'description' => $module->description,
                'icon' => $module->icon,
                'order' => $module->order,
                'color_code' => $module->color_code,
                'progress' => $completionPercentage,
                'total_xp' => $module->getTotalXp(),
            ];
        }, $modules);
    }
}
