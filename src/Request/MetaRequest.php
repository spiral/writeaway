<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;

/**
 * Class MetaRequest
 *
 * @package Spiral\Pieces\Requests
 *
 * @property-read string      $namespace
 * @property-read string      $view
 * @property-read string      $code
 * @property-read string|null $title
 * @property-read string|null $description
 * @property-read string|null $keywords
 * @property-read string|null $html
 */
class MetaRequest extends Filter
{
    protected const SCHEMA = [
        // metadata id
        'namespace'   => 'data:namespace',
        'view'        => 'data:view',
        'code'        => 'data:code',
        // metadata content
        'title'       => 'data:title',
        'description' => 'data:description',
        'keywords'    => 'data:keywords',
        'html'        => 'data:html',
    ];

    protected const VALIDATES = [
        'namespace'   => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ],
        'view'        => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ],
        'code'        => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ],
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
