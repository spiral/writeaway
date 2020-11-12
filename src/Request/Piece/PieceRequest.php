<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Filter;
use Spiral\Writeaway\DTO\PieceID;

class PieceRequest extends Filter
{
    protected const SCHEMA = [
        'name' => 'data:id',
        'type' => 'data:type',
    ];

    protected const VALIDATES = [
        'name' => [
            'notEmpty',
            ['is_string', 'error' => '[[ID should be a string]]']
        ],
        'type' => [
            'notEmpty',
            ['is_string', 'error' => '[[Type should be a string]]']
        ],
    ];

    public function id(): PieceID
    {
        return PieceID::create($this->getField('type'), $this->getField('name'));
    }
}
