<?php
namespace Karlomikus\Multilog;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container as App;
use Karlomikus\Multilog\Contracts\MultilogInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Multilog
 *
 * @author Karlo Mikus <contact@karlomikus.com>
 * @version 1.0.0
 */
class Multilog implements MultilogInterface
{
    private $channels = [];

    private $config;

    private $app;

    /**
     * @param Config $config
     * @param App $app
     */
    public function __construct(Config $config, App $app)
    {
        $this->app = $app;
        $this->config = $config;
        $loggers = $config->get('multilog.channels');
        $this->initLoggers($loggers);
    }

    /**
     * Get monolog instance for a specific channel
     *
     * @param  string $name Channel name
     * @return \Monolog\Logger|null
     */
    public function channel($name)
    {
        // Existing logger instance
        if (array_key_exists($name, $this->channels)) {
            return $this->channels[$name];
        }

        // Check if it is a grouped channel like "industries.acme"
        if (false !== strpos($name, '.')) {
            $group = explode('.', $name)[0] . '.*';
            $loggers = $this->config->get('multilog.channels');

            if (isset($loggers[$group])) {
                $this->createLogger($name, $loggers[$group]);
                return $this->channels[$name];
            }
        }
    }

    /**
     * Alias for channel
     *
     * @param  string $name
     * @return \Monolog\Logger
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
        foreach ($loggers as $channel => $closure) {
            $this->createLogger($channel, $closure);
        }
    }

    /**
     * Store the logger created via closure in with the given channel name
     *
     * @param  string $channel Channel name
     * @param  \Closure $closure  Logger instantiator
     * @return void
     */
    private function createLogger($channel, \Closure $closure)
    {
        $this->channels[$channel] = $closure($channel);
    }
}
