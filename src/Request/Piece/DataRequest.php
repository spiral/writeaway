<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Filter;

class DataRequest extends Filter
{
    protected const SCHEMA = [
        'data' => 'data:data',
    ];

    protected const VALIDATES = [
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

    public function getData(): array
    {
        $data = $this->getField('data');
        return is_array($data) ? $data : [];
    }
}
