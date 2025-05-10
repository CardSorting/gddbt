<?php

namespace App\Application\Queries;

interface QueryHandlerInterface
{
    /**
     * Handle a query.
     *
     * @param QueryInterface $query
     * @return mixed
     */
    public function handle(QueryInterface $query);

    /**
     * Get the query types this handler can handle.
     *
     * @return array
     */
    public function getHandledQueryTypes(): array;
}
