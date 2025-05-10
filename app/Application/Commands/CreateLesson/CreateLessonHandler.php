<?php

namespace App\Application\Commands\CreateLesson;

use App\Application\Commands\CommandHandlerInterface;
use App\Domain\Factories\LessonFactory;
use App\Domain\Repositories\LessonRepositoryInterface;
use App\Domain\ValueObjects\Content\LessonContent;

class CreateLessonHandler implements CommandHandlerInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
        private LessonFactory $lessonFactory
    ) {}

    /**
     * Handle the create lesson command
     * 
     * @param CreateLessonCommand $command
     * @return void
     */
    public function handle(CreateLessonCommand $command): void
    {
        $dto = $command->getLessonDTO();
        
        // Create the lesson with value objects
        $lesson = $this->lessonFactory->create(
            skillId: $dto->skillId,
            name: $dto->name,
            slug: $dto->slug,
            description: $dto->description,
            content: new LessonContent($dto->content),
            order: $dto->order,
            durationMinutes: $dto->durationMinutes,
            xpReward: $dto->xpReward,
            isActive: $dto->isActive,
            isPremium: $dto->isPremium
        );
        
        // Save the lesson
        $this->lessonRepository->save($lesson);
    }
}
