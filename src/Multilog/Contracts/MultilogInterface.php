<?php
namespace Karlomikus\Multilog\Contracts;

/**
 * Multilog Interface
 */
interface MultilogInterface
{
    /**
     * Get monolog instance for a specific channel
     *
     * @param  string $name Channel name
     * @return \Monolog\Logger|null
     */
    public function channel($name);
}
