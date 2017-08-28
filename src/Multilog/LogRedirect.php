<?php
namespace Karlomikus\Multilog;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Karlomikus\Multilog\Contracts\MultilogInterface;

class LogRedirect implements Log
{
    protected $config;
    protected $multilog;

    /**
     * Create a new log writer instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository          $config
     * @param  \Karlomikus\Multilog\Contracts\MultilogInterface $multilog
     * @return void
     */
    public function __construct(Repository $config, MultilogInterface $multilog)
    {
        $this->config = $config;
        $this->multilog = $multilog;
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->logWithDefaultChannel('alert', $message, $context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->logWithDefaultChannel('critical', $message, $context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->logWithDefaultChannel('error', $message, $context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->logWithDefaultChannel('warning', $message, $context);
    }

    /**
     * Log a notice to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->logWithDefaultChannel('notice', $message, $context);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->logWithDefaultChannel('info', $message, $context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->logWithDefaultChannel('debug', $message, $context);
    }

    /**
     * Log a message to the logs.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->logWithDefaultChannel($level, $message, $context);
    }

    /**
     * Register a file log handler.
     *
     * @param  string  $path
     * @param  string  $level
     * @return void
     */
    public function useFiles($path, $level = 'debug')
    {
        throw new \Exception('useFiles() cannot be called with MonologRedirection');
    }

    /**
     * Register a daily file log handler.
     *
     * @param  string  $path
     * @param  int     $days
     * @param  string  $level
     * @return void
     */
    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {
        throw new \Exception('useDailyFiles() cannot be called with MonologRedirection');
    }

    /**
     * Proxy message through Multilog default channel
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    protected function logWithDefaultChannel($level, $message, array $context = [])
    {
        $channel = $this->config->get('multilog.defaultChannel');
        $this->multilog->channel($channel)->$level($message, $context);
    }
}
