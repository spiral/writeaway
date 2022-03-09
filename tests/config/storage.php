<?php

declare(strict_types=1);

return [
    'servers' => [
        'local' => [
            'adapter' => 'local',
            'directory' => dirname(__DIR__) . '/public/uploads',
        ],
    ],

    'buckets' => [
        'uploads' => [
            'server'  => 'local',
            'distribution' => 'local',
        ],
    ]
];
