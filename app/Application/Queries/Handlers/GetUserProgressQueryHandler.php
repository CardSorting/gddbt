<?php

namespace App\Application\Queries\Handlers;

use App\Application\Queries\QueryHandlerInterface;
use App\Application\Queries\QueryInterface;
use App\Application\Queries\GetUserProgressQuery;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\ModuleRepositoryInterface;
use App\Domain\Repositories\SkillRepositoryInterface;
use App\Domain\Repositories\StreakRepositoryInterface;
use App\Domain\Repositories\AchievementRepositoryInterface;
use InvalidArgumentException;

class GetUserProgressQueryHandler implements QueryHandlerInterface
{
    /**
     * The user repository.
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * The module repository.
     *
     * @var ModuleRepositoryInterface
     */
    private ModuleRepositoryInterface $moduleRepository;

    /**
     * The skill repository.
     *
     * @var SkillRepositoryInterface
     */
    private SkillRepositoryInterface $skillRepository;

    /**
     * The streak repository.
     *
     * @var StreakRepositoryInterface
     */
    private StreakRepositoryInterface $streakRepository;

    /**
     * The achievement repository.
     *
     * @var AchievementRepositoryInterface
     */
    private AchievementRepositoryInterface $achievementRepository;

    /**
     * Create a new handler instance.
     *
     * @param UserRepositoryInterface $userRepository
     * @param ModuleRepositoryInterface $moduleRepository
     * @param SkillRepositoryInterface $skillRepository
     * @param StreakRepositoryInterface $streakRepository
     * @param AchievementRepositoryInterface $achievementRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        ModuleRepositoryInterface $moduleRepository,
        SkillRepositoryInterface $skillRepository,
        StreakRepositoryInterface $streakRepository,
        AchievementRepositoryInterface $achievementRepository
    ) {
        $this->userRepository = $userRepository;
        $this->moduleRepository = $moduleRepository;
        $this->skillRepository = $skillRepository;
        $this->streakRepository = $streakRepository;
        $this->achievementRepository = $achievementRepository;
    }

    /**
     * Handle the query.
     *
     * @param QueryInterface $query
     * @return array
     * @throws InvalidArgumentException
     */
    public function handle(QueryInterface $query): array
    {
        if (!$query instanceof GetUserProgressQuery) {
            throw new InvalidArgumentException(
                sprintf('Expected GetUserProgressQuery, got %s', get_class($query))
            );
        }

        $userId = $query->getUserId();
        $moduleId = $query->getModuleId();

        // Get user information
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new InvalidArgumentException("User not found: {$userId}");
        }

        // Get user streak information
        $streak = $this->streakRepository->findByUser($userId);
        
        // Get user achievements
        $achievements = $this->achievementRepository->getEarnedByUser($userId);
        
        // Get module and skill progress data
        if ($moduleId) {
            // If a specific module is requested, get detailed data for that module only
            $module = $this->moduleRepository->find($moduleId);
            if (!$module) {
                throw new InvalidArgumentException("Module not found: {$moduleId}");
            }
            
            $moduleProgress = $module->getCompletionPercentage($userId);
            $skills = $this->skillRepository->getSkillsWithUserProgress($userId, $moduleId);
            
            $moduleData = [
                'id' => $module->id,
                'name' => $module->name,
                'description' => $module->description,
                'progress' => $moduleProgress,
                'skills' => $skills,
            ];
        } else {
            // Otherwise, get summary data for all modules
            $modules = $this->moduleRepository->getModulesWithUserProgress($userId);
            $moduleData = $modules;
        }

        // Prepare response
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'xp_points' => $user->xp_points,
                'level' => $user->level,
            ],
            'streak' => $streak ? [
                'current_count' => $streak->current_count,
                'longest_count' => $streak->longest_count,
                'is_active_today' => $streak->isActiveToday(),
                'freeze_count' => $streak->freeze_count,
            ] : null,
            'achievements' => [
                'count' => count($achievements),
                'recent' => array_slice($achievements, 0, 5), // Get the 5 most recent achievements
            ],
            'modules' => $moduleData,
            'recommended_next' => $this->getRecommendedNextContent($userId),
        ];
    }

    /**
     * Get recommended next content for the user.
     *
     * @param int $userId
     * @return array
     */
    private function getRecommendedNextContent(int $userId): array
    {
        // This would implement a recommendation algorithm based on user progress
        // For now, we'll just return the first few incomplete lessons
        return [
            'lessons' => [], // To be implemented
            'skills' => [],  // To be implemented
        ];
    }

    /**
     * Get the query types this handler can handle.
     *
     * @return array
     */
    public function getHandledQueryTypes(): array
    {
        return ['get_user_progress'];
    }
}
