<?php
/**
 * Storage manager configuration. Attention, configs might include runtime code which depended on
 * environment values only.
 *
 * @see StorageConfig
 */
use Spiral\Storage\Server;

return [
    'servers' => [
        'local'     => [
            'class'   => Server\LocalServer::class,
            'options' => [
                'home' => directory('public')
            ]
        ],
    ],

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
