<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;

/**
 * @property string           $code
 * @property PieceDataRequest $data
 */
class PieceRequest extends Filter
{
    protected const SCHEMA = [
        'code' => 'data:id',
        'data' => PieceDataRequest::class,
    ];

    protected const VALIDATES = [
        'code' => ['notEmpty', 'string']
    ];
}
