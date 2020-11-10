<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;

/**
 * @property-read string          $namespace
 * @property-read string          $view
 * @property-read string          $code
 * @property-read MetaDataRequest $data
 */
class MetaRequest extends Filter
{
    protected const SCHEMA = [
        // metadata id
        'namespace' => 'data:namespace',
        'view'      => 'data:view',
        'code'      => 'data:code',
        'data'      => MetaDataRequest::class,
    ];

    protected const VALIDATES = [
        'namespace' => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ],
        'view'      => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ],
        'code'      => [
            'notEmpty',
            ['is_string', 'error' => '[[Should be a string.]]']
        ]
    ];
}
