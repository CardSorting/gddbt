<?php

namespace App\Application\Queries\Handlers;

use App\Application\Queries\QueryHandlerInterface;
use App\Application\Queries\QueryInterface;
use App\Application\Queries\GetUserDailyGoalsQuery;
use App\Domain\Repositories\DailyGoalRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\SkillRepositoryInterface;
use InvalidArgumentException;

class GetUserDailyGoalsQueryHandler implements QueryHandlerInterface
{
    /**
     * The daily goal repository.
     *
     * @var DailyGoalRepositoryInterface
     */
    private DailyGoalRepositoryInterface $dailyGoalRepository;

    /**
     * The user repository.
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * The skill repository.
     *
     * @var SkillRepositoryInterface
     */
    private SkillRepositoryInterface $skillRepository;

    /**
     * Create a new handler instance.
     *
     * @param DailyGoalRepositoryInterface $dailyGoalRepository
     * @param UserRepositoryInterface $userRepository
     * @param SkillRepositoryInterface $skillRepository
     */
    public function __construct(
        DailyGoalRepositoryInterface $dailyGoalRepository,
        UserRepositoryInterface $userRepository,
        SkillRepositoryInterface $skillRepository
    ) {
        $this->dailyGoalRepository = $dailyGoalRepository;
        $this->userRepository = $userRepository;
        $this->skillRepository = $skillRepository;
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
        if (!$query instanceof GetUserDailyGoalsQuery) {
            throw new InvalidArgumentException(
                sprintf('Expected GetUserDailyGoalsQuery, got %s', get_class($query))
            );
        }

        $userId = $query->getUserId();
        $user = $this->userRepository->find($userId);
        
        if (!$user) {
            throw new InvalidArgumentException("User not found: {$userId}");
        }

        // Get daily goals based on the query parameters
        if ($query->hasDateRange()) {
            $dailyGoals = $this->dailyGoalRepository->getByUserAndDateRange(
                $userId,
                $query->getStartDate(),
                $query->getEndDate()
            );
        } else {
            $dailyGoals = $this->dailyGoalRepository->getByUser(
                $userId,
                $query->getLimit()
            );
        }

        // Format the response
        $formattedGoals = [];
        foreach ($dailyGoals as $goal) {
            // Get skill details if skills are referenced
            $skills = [];
            if (!empty($goal->skills_used)) {
                $skillModels = $this->skillRepository->findMany($goal->skills_used);
                foreach ($skillModels as $skill) {
                    $skills[] = [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'module_id' => $skill->module_id,
                    ];
                }
            }

            $formattedGoals[] = [
                'id' => $goal->id,
                'date' => $goal->date->toDateString(),
                'daily_goal' => $goal->daily_goal,
                'tomorrow_goal' => $goal->tomorrow_goal,
                'highlight' => $goal->highlight,
                'gratitude' => $goal->gratitude,
                'is_public' => $goal->is_public,
                'skills' => $skills,
                'created_at' => $goal->created_at->toDateTimeString(),
                'updated_at' => $goal->updated_at->toDateTimeString(),
            ];
        }

        return [
            'user_id' => $userId,
            'total' => count($formattedGoals),
            'goals' => $formattedGoals,
        ];
    }

    /**
     * Get the query types this handler can handle.
     *
     * @return array
     */
    public function getHandledQueryTypes(): array
    {
        return ['get_user_daily_goals'];
    }
}
