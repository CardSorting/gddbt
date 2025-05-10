<?php

namespace App\Application\Commands\CreateExercise;

use App\Application\Commands\CommandHandlerInterface;
use App\Application\Commands\CommandInterface;
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
     * Get the command types this handler can handle.
     *
     * @return array
     */
    public function getHandledCommandTypes(): array
    {
        return ['create_exercise'];
    }

    /**
     * Handle the create exercise command
     * 
     * @param CommandInterface $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof CreateExerciseCommand) {
            throw new \InvalidArgumentException('Expected CreateExerciseCommand');
        }
        
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
