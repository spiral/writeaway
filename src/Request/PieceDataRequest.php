<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;

/**
 * @property-read string|null $html
 */
class PieceDataRequest extends Filter
{
    protected const SCHEMA = [
        'html' => 'data:html',
    ];

    protected const VALIDATES = [
        'html' => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'html']]
        ],
    ];
}
