<?php

namespace App\Application\Queries;

class GetUserDailyGoalsQuery implements QueryInterface
{
    /**
     * User ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * Limit the number of results.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * Start date for date range (YYYY-MM-DD).
     *
     * @var string|null
     */
    private ?string $startDate;

    /**
     * End date for date range (YYYY-MM-DD).
     *
     * @var string|null
     */
    private ?string $endDate;

    /**
     * Create a new query instance.
     *
     * @param int $userId
     * @param int|null $limit
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(
        int $userId,
        ?int $limit = null,
        ?string $startDate = null,
        ?string $endDate = null
    ) {
        $this->userId = $userId;
        $this->limit = $limit;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType(): string
    {
        return 'get_user_daily_goals';
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
     * Get the limit.
     *
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Get the start date.
     *
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * Get the end date.
     *
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * Determine if a date range was specified.
     *
     * @return bool
     */
    public function hasDateRange(): bool
    {
        return $this->startDate !== null && $this->endDate !== null;
    }
}
