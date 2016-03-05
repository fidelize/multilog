<?php
namespace Karlomikus\Multilog;

use Illuminate\Contracts\Config\Repository as Config;
use Karlomikus\Multilog\Contracts\MultilogInterface;
use Monolog\Logger;

/**
 * Multilog
 *
 * @author Karlo Mikus <contact@karlomikus.com>
 * @version 1.0.0
 */
class Multilog implements MultilogInterface
{
    private $channels;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $loggers = $config->get('multilog');
        $this->initLoggers($loggers);
    }

    /**
     * Get monolog instance for a specific channel
     *
     * @param  string $name Channel name
     * @return Monolog\Logger|null
     */
    public function channel($name)
    {
        if (in_array($name, $this->channels)) {
            return $this->channels[$name];
        }

        return null;
    }

    /**
     * Alias for channel
     *
     * @param  string $name
     * @return Monolog\Logger
     */
    public function c($name)
    {
        return $this->channel($name);
    }

    /**
     * Read config array and initialize
     * all loggers
     *
     * @param  array  $loggers
     * @return void
     */
    private function initLoggers(array $loggers)
    {
        foreach ($loggers as $channel => $logger) {
            $this->createLogger($channel, $config);
        }
    }

    /**
     * Create new logger instance with given
     * configuration and store it in array
     * with channel name
     *
     * @param  string $channel Channel name
     * @param  array $config  Channel configuration
     * @return void
     */
    private function createLogger($channel, array $config)
    {
        $logger = new Logger($channel);

        $this->channels[$channel] = $logger;
    }
}
