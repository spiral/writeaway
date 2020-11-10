<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;

/**
 * @property-read string|null $title
 * @property-read string|null $description
 * @property-read string|null $keywords
 * @property-read string|null $html
 */
class MetaDataRequest extends Filter
{
    protected const SCHEMA = [
        'title'       => 'data:title',
        'description' => 'data:description',
        'keywords'    => 'data:keywords',
        'html'        => 'data:html',
    ];

    protected const VALIDATES = [
        'title'       => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'title']]
        ],
        'description' => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'description']]
        ],
        'keywords'    => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'keywords']]
        ],
        'html'        => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'html']]
        ],
    ];
}
