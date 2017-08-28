<?php
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
|--------------------------------------------------------------------------
| Multilog
|--------------------------------------------------------------------------
|
| Configure your log streams here
|
*/
return [
    // Uncomment to proxy Log::foo() to Multilog::channel('app')->foo()
    // 'defaultChannel' => 'app',

    'channels' => [
        // Usage: Multilog::channel('app')->info('Hello world')
        'app' => function ($channel) {
            $logger = new Logger($channel);
            $logger->pushHandler(new RotatingFileHandler(
                storage_path('/logs/app.log'),
                7,
                Logger::INFO
            ));
            return $logger;
        },

        // Wildcard configuration for any channel starting with "industries.":
        // Usage: Multilog::channel('industries.acme')->info('Hello world')
        //        Multilog::channel('industries.wayne')->info('Foo bar')
        'industries.*' => function ($channel) {
            $logger = new Logger($channel);
            $logger->pushHandler(new StreamHandler(storage_path('/logs/' . $channel . '.log')));
            return $logger;
        },
    ],
];
