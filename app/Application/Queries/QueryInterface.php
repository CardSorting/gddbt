<?php

namespace App\Application\Queries;

interface QueryInterface
{
    /**
     * Get the query type (used for routing the query to the right handler).
     *
     * @return string
     */
    public function getQueryType(): string;
}
