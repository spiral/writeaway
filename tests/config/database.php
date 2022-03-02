<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    'default'   => 'default',
    'databases' => [
        'default' => [
            'driver' => 'sqlite',
        ],
    ],
    'drivers'   => [
        'sqlite' => new Config\SQLiteDriverConfig(
            queryCache: true,
        ),
    ],
];
