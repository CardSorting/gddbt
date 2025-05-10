<?php

namespace App\Application\Queries;

class GetUserProgressQuery implements QueryInterface
{
    /**
     * User ID.
     *
     * @var int
     */
    private int $userId;

    /**
     * Module ID (optional filter).
     *
     * @var int|null
     */
    private ?int $moduleId;

    /**
     * Create a new query instance.
     *
     * @param int $userId
     * @param int|null $moduleId
     */
    public function __construct(int $userId, ?int $moduleId = null)
    {
        $this->userId = $userId;
        $this->moduleId = $moduleId;
    }

    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType(): string
    {
        return 'get_user_progress';
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
     * Get the module ID.
     *
     * @return int|null
     */
    public function getModuleId(): ?int
    {
        return $this->moduleId;
    }
}
