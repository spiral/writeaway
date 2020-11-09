<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;

/**
 * @property string $code
 */
class PieceRequest extends Filter
{
    protected const SCHEMA = [
        'code' => 'data:id',
        'data' => 'data:data'
    ];

    protected const VALIDATES = [
        'code' => ['notEmpty', 'string'],
        'data' => [['array', 'if' => ['withAll' => 'data']]],
    ];

    public function getContent(): string
    {
        $data = $this->getField('data');
        if (!is_array($data)) {
            return '';
        }
        return $data['html'];
    }
}
