<?php

namespace App\Application\Commands\Handlers;

use App\Application\Commands\CommandHandlerInterface;
use App\Application\Commands\CommandInterface;
use App\Application\Commands\CompleteLessonCommand;
use App\Domain\Events\LessonCompletedEvent;
use App\Domain\Repositories\LessonRepositoryInterface;
use App\Domain\Repositories\UserProgressRepositoryInterface;
use App\Domain\Repositories\AchievementRepositoryInterface;
use App\Domain\Repositories\StreakRepositoryInterface;
use InvalidArgumentException;

class CompleteLessonCommandHandler implements CommandHandlerInterface
{
    /**
     * The lesson repository.
     *
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;

    /**
     * The user progress repository.
     *
     * @var UserProgressRepositoryInterface
     */
    private UserProgressRepositoryInterface $userProgressRepository;

    /**
     * The achievement repository.
     *
     * @var AchievementRepositoryInterface
     */
    private AchievementRepositoryInterface $achievementRepository;

    /**
     * The streak repository.
     *
     * @var StreakRepositoryInterface
     */
    private StreakRepositoryInterface $streakRepository;

    /**
     * Create a new handler instance.
     *
     * @param LessonRepositoryInterface $lessonRepository
     * @param UserProgressRepositoryInterface $userProgressRepository
     * @param AchievementRepositoryInterface $achievementRepository
     * @param StreakRepositoryInterface $streakRepository
     */
    public function __construct(
        LessonRepositoryInterface $lessonRepository,
        UserProgressRepositoryInterface $userProgressRepository,
        AchievementRepositoryInterface $achievementRepository,
        StreakRepositoryInterface $streakRepository
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->userProgressRepository = $userProgressRepository;
        $this->achievementRepository = $achievementRepository;
        $this->streakRepository = $streakRepository;
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
        if (!$command instanceof CompleteLessonCommand) {
            throw new InvalidArgumentException(
                sprintf('Expected CompleteLessonCommand, got %s', get_class($command))
            );
        }

        $userId = $command->getUserId();
        $lessonId = $command->getLessonId();
        $score = $command->getScore();

        // Retrieve the lesson
        $lesson = $this->lessonRepository->find($lessonId);
        if (!$lesson) {
            throw new InvalidArgumentException("Lesson not found: {$lessonId}");
        }

        // Record the lesson completion
        $completionDate = now();
        $this->lessonRepository->recordCompletion($userId, $lessonId, $score, $completionDate);

        // Update skill progress
        $skillId = $lesson->skill_id;
        $userProgress = $this->userProgressRepository->findByUserAndSkill($userId, $skillId);
        
        if (!$userProgress) {
            $userProgress = $this->userProgressRepository->create([
                'user_id' => $userId,
                'skill_id' => $skillId,
                'completion_percentage' => 0,
                'completed_lessons' => [],
                'last_activity_at' => $completionDate,
            ]);
        }
        
        $userProgress->updateAfterLessonCompletion($lessonId);

        // Update the user's streak
        $streakResult = $this->streakRepository->updateUserStreak($userId);

        // Check for newly earned achievements
        $newAchievements = $this->achievementRepository->checkAndAwardAchievements($userId);

        // Dispatch domain event
        event(new LessonCompletedEvent($userId, $lessonId, $score));

        return [
            'success' => true,
            'lesson_id' => $lessonId,
            'score' => $score,
            'skill_progress' => $userProgress->completion_percentage,
            'streak_info' => $streakResult,
            'new_achievements' => $newAchievements,
        ];
    }

    /**
     * Get the command types this handler can handle.
     *
     * @return array
     */
    public function getHandledCommandTypes(): array
    {
        return ['complete_lesson'];
    }
}
