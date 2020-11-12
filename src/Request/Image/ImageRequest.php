<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Image;

use Spiral\Filters\Filter;
use Spiral\Writeaway\Database\Image;

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
