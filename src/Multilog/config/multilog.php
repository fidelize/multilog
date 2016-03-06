<?php

/*
|--------------------------------------------------------------------------
| Multilog
|--------------------------------------------------------------------------
|
| Configure your log streams here
|
| Configuration options:
| 'stream' => Log filename
| 'daily' => Use rotating daily log files
| 'format' => (Optional) Define monolog line formatter
|
*/
return [

    // Request channel
    'request' => [
        'stream' => 'request.log',
        'daily'  => true,
        'format' => [
            'date'   => 'Y-m-d H:i:s',
            'output' => "[%datetime%] %message% %context% %extra%\n",
        ],
    ],

    // Info channel
    'info' => [
        'stream' => 'info.log',
        'daily'  => false
    ],

];