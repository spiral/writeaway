<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request\Piece;

use Spiral\Filters\Filter;

/**
 * @property-read string $namespace
 * @property-read string $view
 */
class LocationRequest extends Filter
{
    protected const SCHEMA = [
        'namespace' => 'data:namespace',
        'view'      => 'data:view',
    ];

    protected const VALIDATES = [
        'namespace' => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ],
        'view'      => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ]
    ];
}
