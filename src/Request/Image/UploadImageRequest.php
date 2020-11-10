<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request\Image;

use Psr\Http\Message\UploadedFileInterface;
use Spiral\Filters\Filter;

/**
 * @property-read UploadedFileInterface $image
 */
class UploadImageRequest extends Filter
{
    protected const SCHEMA = [
        'image' => 'file:image',
    ];

    protected const VALIDATES = [
        'image' => ['file::uploaded', 'image::valid'],
    ];
}
