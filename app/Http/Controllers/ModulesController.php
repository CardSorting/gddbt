<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\Repositories\ModuleRepositoryInterface;
use App\Domain\Repositories\UserProgressRepositoryInterface;
use App\Application\Queries\GetUserProgressQuery;
use App\Application\Queries\QueryBus;

class ModulesController extends Controller
{
    /**
     * The module repository instance.
     *
     * @var ModuleRepositoryInterface
     */
    protected $moduleRepository;

    /**
     * The user progress repository instance.
     *
     * @var UserProgressRepositoryInterface
     */
    protected $userProgressRepository;

    /**
     * The query bus instance.
     *
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * Create a new controller instance.
     *
     * @param ModuleRepositoryInterface $moduleRepository
     * @param UserProgressRepositoryInterface $userProgressRepository
     * @param QueryBus $queryBus
     * @return void
     */
    public function __construct(
        ModuleRepositoryInterface $moduleRepository,
        UserProgressRepositoryInterface $userProgressRepository,
        QueryBus $queryBus
    ) {
        $this->moduleRepository = $moduleRepository;
        $this->userProgressRepository = $userProgressRepository;
        $this->queryBus = $queryBus;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the modules.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        try {
            // Get all modules with their data - try both methods to ensure we get data
            \Log::debug('ModulesController: Attempting to fetch modules with all()');
            $modules = $this->moduleRepository->all();
            \Log::debug('ModulesController: After all() call, modules count: ' . count($modules));
            
            // If all() method returns empty, try the getAllActive method as a fallback
            if (empty($modules)) {
                \Log::debug('ModulesController: all() method returned empty array, trying getAllActive()');
                $modules = $this->moduleRepository->getAllActive();
                \Log::debug('ModulesController: After getAllActive() call, modules count: ' . count($modules));
            }
            
            // Debug dump of returned modules
            \Log::debug('ModulesController: Raw modules data: ' . json_encode($modules));
            
            // Log the count of modules for debugging
            \Log::info('ModulesController: Retrieved ' . count($modules) . ' modules');
            
            // Get user progress using the CQRS query
            $query = new GetUserProgressQuery($userId);
            $userProgress = $this->queryBus->dispatch($query);
        } catch (\Exception $e) {
            \Log::error('Error in ModulesController index: ' . $e->getMessage());
            $modules = [];
            $userProgress = ['modules' => []];
        }
        
        // Calculate overall progress across all modules
        $completedModulesCount = 0;
        $totalModulesCount = count($modules);
        $overallPercentage = 0;
        
        // Format module data for the view
        $moduleData = [];
        foreach ($modules as $module) {
            $moduleProgress = 0;
            $completedLessonsCount = 0;
            $totalLessonsCount = 0;
            $lastActivityDate = null;
            
            // Find this module's skills and calculate progress
            if (isset($userProgress['modules'])) {
                foreach ($userProgress['modules'] as $progressModule) {
                    if ($progressModule['id'] == $module->id) {
                        $moduleProgress = $progressModule['progress'] ?? 0;
                        
                        // Count completed lessons
                        if (isset($progressModule['skills'])) {
                            foreach ($progressModule['skills'] as $skill) {
                                $completedLessonsCount += count($skill['completed_lessons'] ?? []);
                                $totalLessonsCount += $skill['lesson_count'] ?? 0;
                                
                                // Track last activity date
                                if (isset($skill['last_activity_at']) && 
                                    ($lastActivityDate === null || $skill['last_activity_at'] > $lastActivityDate)) {
                                    $lastActivityDate = $skill['last_activity_at'];
                                }
                            }
                        }
                        
                        break;
                    }
                }
            }
            
            // Consider a module completed if progress is 100%
            if ($moduleProgress >= 100) {
                $completedModulesCount++;
            }
            
            // Set the status for the module
            // First module should always be unlocked, others should be locked if no progress
            $moduleStatus = $moduleProgress >= 100 ? 'completed' : 
                           ($moduleProgress > 0 ? 'in_progress' : 
                           ($module->order == 1 ? 'in_progress' : 'locked'));
                           
            // For the total lesson count, use default counts for each module if database count is 0
            // These counts are based on the content in DbtContentSeeder.php
            $defaultLessonCounts = [
                'Mindfulness' => 2,
                'Distress Tolerance' => 1,
                'Emotion Regulation' => 1,
                'Interpersonal Effectiveness' => 1
            ];
            
            $defaultTotalLessons = $defaultLessonCounts[$module->name] ?? 0;
            $actualTotalLessons = $totalLessonsCount > 0 ? $totalLessonsCount : $defaultTotalLessons;
                           
            // Add module data to array
            $moduleData[] = [
                'id' => $module->id,
                'name' => $module->name,
                'slug' => $module->slug ?? strtolower(str_replace(' ', '-', $module->name)),
                'description' => $module->description,
                'completion_percentage' => $moduleProgress,
                'completed_lessons_count' => $completedLessonsCount,
                'total_lessons_count' => $actualTotalLessons,
                'last_activity_at' => $lastActivityDate,
                'status' => $moduleStatus,
                'color_code' => $module->color_code,
                'icon' => $module->icon,
                'order' => $module->order
            ];
            
            // Add to overall progress
            $overallPercentage += $moduleProgress;
        }
        
        // Calculate overall percentage
        if ($totalModulesCount > 0) {
            $overallPercentage = $overallPercentage / $totalModulesCount;
        }
        
        // Prepare view data
        $viewData = [
            'modules' => $moduleData,
            'overall_progress' => [
                'completed_count' => $completedModulesCount,
                'total_count' => $totalModulesCount,
                'completion_percentage' => round($overallPercentage)
            ]
        ];
        
        return view('modules.index', $viewData);
    }

    /**
     * Display the specified module.
     *
     * @param Request $request
     * @param string $module
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request, $module)
    {
        $userId = Auth::id();
        
        // Get module data - convert slug to proper format if needed
        $moduleData = $this->moduleRepository->findBySlug($module);
        
        if (!$moduleData) {
            abort(404);
        }
        
        // Get user progress specific to this module
        $query = new GetUserProgressQuery($userId, $moduleData->id);
        $userProgress = $this->queryBus->dispatch($query);
        
        // Format module data for the view
        $moduleProgress = 0;
        $completedLessons = [];
        $lastActivityAt = null;
        
        if (isset($userProgress['modules']) && !empty($userProgress['modules'])) {
            $moduleProgress = $userProgress['modules'][0]['progress'] ?? 0;
            
            // Get completed lessons and last activity date across all skills
            if (isset($userProgress['modules'][0]['skills'])) {
                foreach ($userProgress['modules'][0]['skills'] as $skill) {
                    if (isset($skill['completed_lessons'])) {
                        $completedLessons = array_merge($completedLessons, $skill['completed_lessons']);
                    }
                    
                    if (isset($skill['last_activity_at']) && 
                        ($lastActivityAt === null || $skill['last_activity_at'] > $lastActivityAt)) {
                        $lastActivityAt = $skill['last_activity_at'];
                    }
                }
            }
        }
        
        // Get all lessons for this module
        $lessons = [];
        
        // Create a placeholder order counter
        $lessonOrderCounter = 1;
        
        if ($moduleData->skills) {
            foreach ($moduleData->skills as $skill) {
                $foundLessons = false;
                
                if ($skill->lessons && $skill->lessons->count() > 0) {
                    foreach ($skill->lessons as $lesson) {
                        $lessons[] = [
                            'id' => $lesson->id,
                            'title' => $lesson->title ?? 'Lesson ' . $lessonOrderCounter,
                            'description' => $lesson->description ?? 'Learn about ' . $skill->name,
                            'order' => $lesson->order ?? $lessonOrderCounter,
                            'is_completed' => in_array($lesson->id, $completedLessons),
                            'skill_name' => $skill->name
                        ];
                        $lessonOrderCounter++;
                        $foundLessons = true;
                    }
                }
                
                // If no lessons were found for this skill, create a default one
                if (!$foundLessons) {
                    $lessons[] = [
                        'id' => 'default-' . $skill->id,
                        'title' => 'Introduction to ' . $skill->name,
                        'description' => 'Learn the fundamentals of ' . $skill->name,
                        'order' => $lessonOrderCounter,
                        'is_completed' => false,
                        'skill_name' => $skill->name,
                        'is_default' => true
                    ];
                    $lessonOrderCounter++;
                }
            }
            
            // Sort lessons by order
            usort($lessons, function($a, $b) {
                return $a['order'] <=> $b['order'];
            });
        }
        
        // If we still have no lessons, create a default lesson for the module
        if (empty($lessons)) {
            $lessons[] = [
                'id' => 'default-module-' . $moduleData->id,
                'title' => 'Introduction to ' . $moduleData->name,
                'description' => 'Learn the fundamentals of ' . $moduleData->name,
                'order' => 1,
                'is_completed' => false,
                'skill_name' => $moduleData->name,
                'is_default' => true
            ];
        }
        
        // Calculate first incomplete lesson (if any)
        $activeLesson = null;
        foreach ($lessons as $index => $lesson) {
            if (!$lesson['is_completed']) {
                $activeLesson = $index;
                break;
            }
        }
        
        // Prepare view data
        $viewData = [
            'module' => $module,
            'moduleData' => $moduleData,
            'lessons' => $lessons,
            'activeLesson' => $activeLesson,
            'progress' => [
                'completion_percentage' => $moduleProgress,
                'completed_lessons' => $completedLessons,
                'last_activity_at' => $lastActivityAt ? new \DateTime($lastActivityAt) : null,
                'status' => $moduleProgress >= 100 ? 'completed' : ($moduleProgress > 0 ? 'in_progress' : 'not_started')
            ]
        ];
        
        return view('modules.show', $viewData);
    }
}
