<?php

declare(strict_types=1);

return [
    'buckets' => [
        'uploads' => [
            'server'  => 'local',
            'prefix'  => '/uploads/',
            'options' => [
                //Directory has to be specified relatively to root directory of associated server
                'directory' => 'uploads/'
            ]
        ],
    ]
];
