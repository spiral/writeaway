<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request\Piece;

use Spiral\Filters\Filter;
use Spiral\WriteAway\Model\PieceID;

class PieceRequest extends Filter
{
    protected const SCHEMA = [
        'code' => 'data:id',
        'type' => 'data:type',
    ];

    protected const VALIDATES = [
        'code' => [
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
        return PieceID::create($this->getField('type'), $this->getField('code'));
    }
}
