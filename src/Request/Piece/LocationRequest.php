<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Filter;
use Spiral\Writeaway\DTO\Location;

class LocationRequest extends Filter
{
    protected const SCHEMA = [
        'namespace' => 'data:namespace',
        'view'      => 'data:view',
    ];

    protected const VALIDATES = [
        'namespace' => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'namespace']]
        ],
        'view'      => [
            ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'view']]
        ]
    ];

    protected const SETTERS = [
        'namespace' => ['self', 'toStringIfEmpty'],
        'view'      => ['self', 'toStringIfEmpty'],
    ];

    public function location(): Location
    {
        return new Location((string)$this->getField('namespace'), (string)$this->getField('view'));
    }

    protected function toStringIfEmpty($value)
    {
        return $value ?: '';
    }
}
