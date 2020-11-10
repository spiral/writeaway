<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request\Piece;

use Spiral\Filters\Filter;

/**
 * @property string $id
 * @property string $type
 * @property array  $data
 */
class PieceRequest extends Filter
{
    protected const SCHEMA = [
        'id'   => 'data:id',
        'type' => 'data:type',
        'data' => 'data:data',
    ];

    protected const VALIDATES = [
        'id'   => [
            'notEmpty',
            ['is_string', 'error' => '[[ID should be a string]]'],
            ['piece:id', 'type']
        ],
        'type' => [
            'notEmpty',
            ['is_string', 'error' => '[[Type should be a string]]']
        ],
        'data' => [
            ['is_array', 'error' => '[[Data should be an array.]]', 'if' => ['withAll' => 'data']]
        ],
    ];

    protected const SETTERS = [
        'data' => ['self', 'toArrayIfEmpty']
    ];

    protected function toArrayIfEmpty($input)
    {
        return $input ?: [];
    }
}
