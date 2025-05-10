<?php

namespace App\Application\Queries;

use InvalidArgumentException;

class QueryBus
{
    /**
     * The registered query handlers.
     *
     * @var array
     */
    private array $handlers = [];

    /**
     * Register a query handler.
     *
     * @param QueryHandlerInterface $handler
     * @return void
     */
    public function registerHandler(QueryHandlerInterface $handler): void
    {
        foreach ($handler->getHandledQueryTypes() as $queryType) {
            $this->handlers[$queryType] = $handler;
        }
    }

    /**
     * Dispatch a query to its appropriate handler.
     *
     * @param QueryInterface $query
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function dispatch(QueryInterface $query)
    {
        $queryType = $query->getQueryType();

        if (!isset($this->handlers[$queryType])) {
            throw new InvalidArgumentException("No handler registered for query type: {$queryType}");
        }

        return $this->handlers[$queryType]->handle($query);
    }

    /**
     * Check if a handler exists for a query type.
     *
     * @param string $queryType
     * @return bool
     */
    public function hasHandlerFor(string $queryType): bool
    {
        return isset($this->handlers[$queryType]);
    }

    /**
     * Get all registered query types.
     *
     * @return array
     */
    public function getRegisteredQueryTypes(): array
    {
        return array_keys($this->handlers);
    }
}
