<?php

namespace App\Application\Commands;

interface CommandHandlerInterface
{
    /**
     * Handle a command.
     *
     * @param CommandInterface $command
     * @return mixed
     */
    public function handle(CommandInterface $command);

    /**
     * Get the command types this handler can handle.
     *
     * @return array
     */
    public function getHandledCommandTypes(): array;
}
