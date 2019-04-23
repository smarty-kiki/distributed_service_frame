<?php

return [

    'midwares' => [
        'idgenter' => 'local',
    ],

    'resources' => [
        'local' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 1,
            'options' => [
                Redis::OPT_SERIALIZER => Redis::SERIALIZER_PHP,
            ],
        ],
    ],
];
