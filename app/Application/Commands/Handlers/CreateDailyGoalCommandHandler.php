<?php

namespace App\Application\Commands\Handlers;

use App\Application\Commands\CommandHandlerInterface;
use App\Application\Commands\CommandInterface;
use App\Application\Commands\CreateDailyGoalCommand;
use App\Domain\Repositories\DailyGoalRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Models\DailyGoal;
use InvalidArgumentException;

class CreateDailyGoalCommandHandler implements CommandHandlerInterface
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
     * Create a new handler instance.
     *
     * @param DailyGoalRepositoryInterface $dailyGoalRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        DailyGoalRepositoryInterface $dailyGoalRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->dailyGoalRepository = $dailyGoalRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the command.
     *
     * @param CommandInterface $command
     * @return array
     * @throws InvalidArgumentException
     */
    public function handle(CommandInterface $command): array
    {
        if (!$command instanceof CreateDailyGoalCommand) {
            throw new InvalidArgumentException(
                sprintf('Expected CreateDailyGoalCommand, got %s', get_class($command))
            );
        }

        $userId = $command->getUserId();
        $user = $this->userRepository->find($userId);
        
        if (!$user) {
            throw new InvalidArgumentException("User not found: {$userId}");
        }

        $data = [
            'daily_goal' => $command->getDailyGoal(),
            'skills_used' => $command->getSkillsUsed(),
            'tomorrow_goal' => $command->getTomorrowGoal(),
            'highlight' => $command->getHighlight(),
            'gratitude' => $command->getGratitude(),
            'is_public' => $command->isPublic(),
        ];

        // Create or update the daily goal for today
        $dailyGoal = $this->dailyGoalRepository->createOrUpdateToday($userId, $data);

        // Check if the daily goal helped complete any streak
        if ($user->streak) {
            $streakUpdated = $user->streak->markActivityToday();
        }

        return [
            'success' => true,
            'daily_goal' => [
                'id' => $dailyGoal->id,
                'date' => $dailyGoal->date->toDateString(),
                'daily_goal' => $dailyGoal->daily_goal,
                'tomorrow_goal' => $dailyGoal->tomorrow_goal,
                'highlight' => $dailyGoal->highlight,
                'gratitude' => $dailyGoal->gratitude,
                'is_public' => $dailyGoal->is_public,
                'skills_used' => $dailyGoal->skills_used,
            ],
            'streak_updated' => $streakUpdated ?? false,
        ];
    }

    /**
     * Get the command types this handler can handle.
     *
     * @return array
     */
    public function getHandledCommandTypes(): array
    {
        return ['create_daily_goal'];
    }
}
