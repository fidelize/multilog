<?php
namespace Karlomikus\Multilog;

use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('log', function () {
            return $this->createLogger();
        });
    }

    /**
     * Create the logger.
     *
     * @return \Illuminate\LogRedirect
     */
    public function createLogger()
    {
        return $this->app->make(LogRedirect::class);
    }
}
