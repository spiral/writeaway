<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Image;

use Psr\Http\Message\UploadedFileInterface;
use Spiral\Filters\Attribute\Input\File;
use Spiral\Filters\Model\Filter;
use Spiral\Filters\Model\FilterDefinitionInterface;
use Spiral\Filters\Model\HasFilterDefinition;
use Spiral\Validator\FilterDefinition;

class UploadImageRequest extends Filter implements HasFilterDefinition
{
    #[File]
    public readonly UploadedFileInterface $image;

    public function filterDefinition(): FilterDefinitionInterface
    {
        return new FilterDefinition([
            'image' => ['file::uploaded', 'image::valid']
        ]);
    }
}
