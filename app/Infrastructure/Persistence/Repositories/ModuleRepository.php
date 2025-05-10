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
        // First try direct slug match with eager loading
        $module = Module::with(['skills.lessons'])
            ->where('slug', $slug)
            ->first();
        
        if (!$module) {
            // Try matching by converting name to slug format
            $module = Module::with(['skills.lessons'])
                ->get()
                ->first(function($m) use ($slug) {
                    return strtolower(str_replace(' ', '-', $m->name)) === $slug;
                });
        }
        
        return $module;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all(): array
    {
        try {
            \Log::debug('ModuleRepository::all() - Beginning execution');
            
            // Get raw SQL query for debugging
            $query = Module::orderBy('order');
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            \Log::debug('ModuleRepository::all() - Query: ' . $sql . ' with bindings: ' . json_encode($bindings));
            
            // Count modules directly from DB to bypass any potential model issues
            $dbCount = \DB::table('modules')->count();
            \Log::debug('ModuleRepository::all() - Direct DB count: ' . $dbCount);
            
            // Use standard orderBy for better cross-database compatibility
            $modules = Module::orderBy('order')->get();
            \Log::debug('ModuleRepository::all() - Retrieved ' . count($modules) . ' modules from Eloquent');
            
            // Debug output of retrieved modules
            foreach ($modules as $index => $module) {
                \Log::debug("ModuleRepository::all() - Module #{$index}: ID={$module->id}, Name={$module->name}, Order={$module->order}");
            }
            
            return $modules ? $modules->all() : [];
        } catch (\Exception $e) {
            // Log the error but return a valid empty array
            // to prevent null being returned
            \Log::error('Error retrieving modules: ' . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return [];
        }
    }

    /**
     * Get all active modules ordered by their display order.
     *
     * @return array
     */
    public function getAllActive(): array
    {
        try {
            // Use correct PostgreSQL syntax for reserved keywords
            return Module::where('is_active', true)
                ->orderBy('order')  // Using standard orderBy instead of orderByRaw
                ->get()
                ->all();
        } catch (\Exception $e) {
            // Log the error but return a valid empty array
            // to prevent null being returned
            \Log::error('Error retrieving active modules: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get modules with their skills.
     *
     * @param bool $activeOnly Whether to include only active modules
     * @return array
     */
    public function getAllWithSkills(bool $activeOnly = true): array
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error retrieving modules with skills: ' . $e->getMessage());
            return [];
        }
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
        try {
            $currentModule = $this->find($currentModuleId);
            if (!$currentModule) {
                return null;
            }

            return Module::where('order', '>', $currentModule->order)
                ->where('is_active', true)
                ->orderBy('order')
                ->first();
        } catch (\Exception $e) {
            \Log::error('Error retrieving next module: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get modules with completion statistics for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getModulesWithUserProgress(int $userId): array
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error retrieving modules with user progress: ' . $e->getMessage());
            return [];
        }
    }
}
