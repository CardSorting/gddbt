<?php

namespace App\Application\Commands;

use InvalidArgumentException;

class CommandBus
{
    /**
     * The registered command handlers.
     *
     * @var array
     */
    private array $handlers = [];

    /**
     * Register a command handler.
     *
     * @param CommandHandlerInterface $handler
     * @return void
     */
    public function registerHandler(CommandHandlerInterface $handler): void
    {
        foreach ($handler->getHandledCommandTypes() as $commandType) {
            $this->handlers[$commandType] = $handler;
        }
    }

    /**
     * Dispatch a command to its appropriate handler.
     *
     * @param CommandInterface $command
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function dispatch(CommandInterface $command)
    {
        $commandType = $command->getCommandType();

        if (!isset($this->handlers[$commandType])) {
            throw new InvalidArgumentException("No handler registered for command type: {$commandType}");
        }

        return $this->handlers[$commandType]->handle($command);
    }

    /**
     * Check if a handler exists for a command type.
     *
     * @param string $commandType
     * @return bool
     */
    public function hasHandlerFor(string $commandType): bool
    {
        return isset($this->handlers[$commandType]);
    }

    /**
     * Get all registered command types.
     *
     * @return array
     */
    public function getRegisteredCommandTypes(): array
    {
        return array_keys($this->handlers);
    }
}
