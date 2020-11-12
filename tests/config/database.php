<?php

declare(strict_types=1);

use Spiral\Database\Driver\SQLite\SQLiteDriver;

return [
    'default'   => 'default',
    'databases' => [
        'default' => [
            'driver' => 'sqlite',
        ],
    ],
    'drivers'   => [
        'sqlite' => [
            'driver'     => SQLiteDriver::class,
            'connection' => 'sqlite::memory:'
        ],
    ],
];
