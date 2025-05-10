<?php

namespace App\Application\Commands;

class CreateDailyGoalCommand implements CommandInterface
{
    /**
     * User ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * Skills used (array of skill IDs).
     *
     * @var array|null
     */
    private ?array $skillsUsed;

    /**
     * Daily goal.
     *
     * @var string
     */
    private string $dailyGoal;

    /**
     * Goal for tomorrow.
     *
     * @var string|null
     */
    private ?string $tomorrowGoal;

    /**
     * Highlight of the day.
     *
     * @var string|null
     */
    private ?string $highlight;

    /**
     * Gratitude entry.
     *
     * @var string|null
     */
    private ?string $gratitude;

    /**
     * Whether the goal is public.
     *
     * @var bool
     */
    private bool $isPublic;

    /**
     * Create a new command instance.
     *
     * @param int $userId
     * @param string $dailyGoal
     * @param array|null $skillsUsed
     * @param string|null $tomorrowGoal
     * @param string|null $highlight
     * @param string|null $gratitude
     * @param bool $isPublic
     */
    public function __construct(
        int $userId,
        string $dailyGoal,
        ?array $skillsUsed = null,
        ?string $tomorrowGoal = null,
        ?string $highlight = null,
        ?string $gratitude = null,
        bool $isPublic = true
    ) {
        $this->userId = $userId;
        $this->dailyGoal = $dailyGoal;
        $this->skillsUsed = $skillsUsed;
        $this->tomorrowGoal = $tomorrowGoal;
        $this->highlight = $highlight;
        $this->gratitude = $gratitude;
        $this->isPublic = $isPublic;
    }

    /**
     * Get the command type.
     *
     * @return string
     */
    public function getCommandType(): string
    {
        return 'create_daily_goal';
    }

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Get the skills used.
     *
     * @return array|null
     */
    public function getSkillsUsed(): ?array
    {
        return $this->skillsUsed;
    }

    /**
     * Get the daily goal.
     *
     * @return string
     */
    public function getDailyGoal(): string
    {
        return $this->dailyGoal;
    }

    /**
     * Get the goal for tomorrow.
     *
     * @return string|null
     */
    public function getTomorrowGoal(): ?string
    {
        return $this->tomorrowGoal;
    }

    /**
     * Get the highlight.
     *
     * @return string|null
     */
    public function getHighlight(): ?string
    {
        return $this->highlight;
    }

    /**
     * Get the gratitude.
     *
     * @return string|null
     */
    public function getGratitude(): ?string
    {
        return $this->gratitude;
    }

    /**
     * Get whether the goal is public.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }
}
