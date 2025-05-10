<?php

namespace App\Application\Commands;

interface CommandInterface
{
    /**
     * Get the command type (used for logging and tracing).
     *
     * @return string
     */
    public function getCommandType(): string;
}
