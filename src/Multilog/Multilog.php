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

    private $app;

    /**
     * @param Config $config
     * @param App $app
     */
    public function __construct(Config $config, App $app)
    {
        $this->app = $app;
        $loggers = $config->get('multilog');
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
        if (array_key_exists($name, $this->channels)) {
            return $this->channels[$name];
        }

        return null;
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
        foreach ($loggers as $channel => $config) {
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
        // Setup configuration
        // Use default laravel logs path
        $storagePath = $this->app->make('path.storage');
        $filepath = $storagePath . '/storage/logs/' . $config['stream'];

        $logger = new Logger($channel);
        $handler = new StreamHandler($filepath);

        // Daily rotation
        if ($config['daily']) {
            $handler = new RotatingFileHandler($filepath, 1);
        }

        // Format line
        if (isset($config['format'])) {
            $format = $config['format'];
            $handler->setFormatter(new LineFormatter($format['output'], $format['date']));
        }

        $logger->pushHandler($handler);

        $this->channels[$channel] = $logger;
    }
}
