<?php

return [

    'request' => [
        'stream' => 'request.log',
        'daily'  => false,
        'format' => [
            'date'   => 'Y-m-d H:i:s',
            'output' => "[%datetime%] %message% %context% %extra%\n",
        ],
    ],

];