<?php

namespace App\Application\Commands\CreateExercise;

use App\Application\Commands\CommandHandlerInterface;
use App\Domain\Factories\ExerciseFactory;
use App\Domain\Repositories\ExerciseRepositoryInterface;
use App\Domain\ValueObjects\Content\ExerciseContent;

class CreateExerciseHandler implements CommandHandlerInterface
{
    public function __construct(
        private ExerciseRepositoryInterface $exerciseRepository,
        private ExerciseFactory $exerciseFactory
    ) {}

    /**
     * Handle the create exercise command
     * 
     * @param CreateExerciseCommand $command
     * @return void
     */
    public function handle(CreateExerciseCommand $command): void
    {
        $dto = $command->getExerciseDTO();
        
        // Create the exercise with value objects
        $exercise = $this->exerciseFactory->create(
            lessonId: $dto->lessonId,
            title: $dto->title,
            description: $dto->description,
            type: $dto->type,
            content: new ExerciseContent(
                $dto->content, 
                $dto->options, 
                $dto->correctAnswer
            ),
            options: $dto->options,
            correctAnswer: $dto->correctAnswer,
            order: $dto->order,
            difficulty: $dto->difficulty,
            xpReward: $dto->xpReward,
            isActive: $dto->isActive
        );
        
        // Save the exercise
        $this->exerciseRepository->save($exercise);
    }
}
