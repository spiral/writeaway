<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request;

use Spiral\Filters\Filter;
use Spiral\WriteAway\Database\Image;

/**
 * @property-read int $id
 */
class ImageRequest extends Filter
{
    protected const SCHEMA = [
        'id' => 'data:id',
    ];

    protected const VALIDATES = [
        'id' => [
            'notEmpty',
            ['entity:exists', Image::class, 'error' => '[[Image not exists.]]']
        ],
    ];
}
