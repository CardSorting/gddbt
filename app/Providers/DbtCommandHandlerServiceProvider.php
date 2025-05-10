<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application\Commands\CreateLesson\CreateLessonCommand;
use App\Application\Commands\CreateLesson\CreateLessonHandler;
use App\Application\Commands\CreateExercise\CreateExerciseCommand;
use App\Application\Commands\CreateExercise\CreateExerciseHandler;
use App\Domain\Factories\LessonFactory;
use App\Domain\Factories\ExerciseFactory;

class DbtCommandHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register Factories
        $this->app->bind(LessonFactory::class, function ($app) {
            return new LessonFactory();
        });
        
        $this->app->bind(ExerciseFactory::class, function ($app) {
            return new ExerciseFactory();
        });
        
        // Register Command Handlers
        $this->app->tag([
            CreateLessonHandler::class,
            CreateExerciseHandler::class,
        ], 'command_handlers');
        
        // Register Command -> Handler mappings
        $this->app->when(CreateLessonHandler::class)
            ->needs('$command')
            ->give(CreateLessonCommand::class);
            
        $this->app->when(CreateExerciseHandler::class)
            ->needs('$command')
            ->give(CreateExerciseCommand::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
