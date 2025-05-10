<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Domain repository interfaces
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\ModuleRepositoryInterface;
use App\Domain\Repositories\SkillRepositoryInterface;
use App\Domain\Repositories\LessonRepositoryInterface;
use App\Domain\Repositories\ExerciseRepositoryInterface;
use App\Domain\Repositories\UserProgressRepositoryInterface;
use App\Domain\Repositories\StreakRepositoryInterface;
use App\Domain\Repositories\AchievementRepositoryInterface;
use App\Domain\Repositories\DailyGoalRepositoryInterface;

// Infrastructure implementations
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\ModuleRepository;
use App\Infrastructure\Persistence\Repositories\SkillRepository;
use App\Infrastructure\Persistence\Repositories\LessonRepository;
use App\Infrastructure\Persistence\Repositories\ExerciseRepository;
use App\Infrastructure\Persistence\Repositories\UserProgressRepository;
use App\Infrastructure\Persistence\Repositories\StreakRepository;
use App\Infrastructure\Persistence\Repositories\AchievementRepository;
use App\Infrastructure\Persistence\Repositories\DailyGoalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interfaces to their implementations
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(ExerciseRepositoryInterface::class, ExerciseRepository::class);
        $this->app->bind(UserProgressRepositoryInterface::class, UserProgressRepository::class);
        $this->app->bind(StreakRepositoryInterface::class, StreakRepository::class);
        $this->app->bind(AchievementRepositoryInterface::class, AchievementRepository::class);
        $this->app->bind(DailyGoalRepositoryInterface::class, DailyGoalRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
