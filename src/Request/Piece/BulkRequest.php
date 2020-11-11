<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request\Piece;

use Spiral\Filters\Filter;
use Spiral\WriteAway\Model\PieceID;

class BulkRequest extends Filter
{
    protected const SCHEMA = [
        'names' => 'data:id',
        'type'  => 'data:type',
    ];

    protected const VALIDATES = [
        'names' => [
            'notEmpty',
            ['is_array', 'error' => '[[ID should be an array of strings]]']
        ],
        'type'  => [
            'notEmpty',
            ['is_string', 'error' => '[[Type should be a string]]']
        ]
    ];

    /**
     * @return PieceID[]
     */
    public function ids(): array
    {
        $type = $this->getField('type');
        return array_map(
            static function (string $code) use ($type): PieceID {
                return PieceID::create($type, $code);
            },
            $names = $this->getField('names')
        );
    }
}
