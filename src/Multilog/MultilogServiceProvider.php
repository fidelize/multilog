<?php
namespace Karlomikus\Multilog;

use Illuminate\Support\ServiceProvider;

class MultilogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Karlomikus\Multilog\Contracts\MultilogInterface',
            'Karlomikus\Multilog\Multilog'
        );

        $this->publishes([
            __DIR__ . '/config/multilog.php' => config_path('multilog.php'),
        ]);
    }
}
