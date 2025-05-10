<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application\Commands\CommandBus;
use App\Application\Queries\QueryBus;

// Command Handlers
use App\Application\Commands\Handlers\CompleteLessonCommandHandler;
use App\Application\Commands\Handlers\CreateDailyGoalCommandHandler;

// Query Handlers
use App\Application\Queries\Handlers\GetUserProgressQueryHandler;
use App\Application\Queries\Handlers\GetUserDailyGoalsQueryHandler;

// Repositories
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\ModuleRepositoryInterface;
use App\Domain\Repositories\SkillRepositoryInterface;
use App\Domain\Repositories\LessonRepositoryInterface;
use App\Domain\Repositories\ExerciseRepositoryInterface;
use App\Domain\Repositories\UserProgressRepositoryInterface;
use App\Domain\Repositories\StreakRepositoryInterface;
use App\Domain\Repositories\AchievementRepositoryInterface;

class CqrsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Command Bus as a singleton
        $this->app->singleton(CommandBus::class, function ($app) {
            $commandBus = new CommandBus();
            
            // Register Command Handlers
            $commandBus->registerHandler($app->make(CompleteLessonCommandHandler::class));
            $commandBus->registerHandler($app->make(CreateDailyGoalCommandHandler::class));
            
            // Add more command handlers here as needed
            
            return $commandBus;
        });

        // Register Query Bus as a singleton
        $this->app->singleton(QueryBus::class, function ($app) {
            $queryBus = new QueryBus();
            
            // Register Query Handlers
            $queryBus->registerHandler($app->make(GetUserProgressQueryHandler::class));
            $queryBus->registerHandler($app->make(GetUserDailyGoalsQueryHandler::class));
            
            // Add more query handlers here as needed
            
            return $queryBus;
        });

        // Register Command Handlers with their dependencies
        $this->app->bind(CompleteLessonCommandHandler::class, function ($app) {
            return new CompleteLessonCommandHandler(
                $app->make(LessonRepositoryInterface::class),
                $app->make(UserProgressRepositoryInterface::class),
                $app->make(AchievementRepositoryInterface::class),
                $app->make(StreakRepositoryInterface::class)
            );
        });
        
        $this->app->bind(CreateDailyGoalCommandHandler::class, function ($app) {
            return new CreateDailyGoalCommandHandler(
                $app->make(DailyGoalRepositoryInterface::class),
                $app->make(UserRepositoryInterface::class)
            );
        });

        // Register Query Handlers with their dependencies
        $this->app->bind(GetUserProgressQueryHandler::class, function ($app) {
            return new GetUserProgressQueryHandler(
                $app->make(UserRepositoryInterface::class),
                $app->make(ModuleRepositoryInterface::class),
                $app->make(SkillRepositoryInterface::class),
                $app->make(StreakRepositoryInterface::class),
                $app->make(AchievementRepositoryInterface::class)
            );
        });
        
        $this->app->bind(GetUserDailyGoalsQueryHandler::class, function ($app) {
            return new GetUserDailyGoalsQueryHandler(
                $app->make(DailyGoalRepositoryInterface::class),
                $app->make(UserRepositoryInterface::class),
                $app->make(SkillRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
