<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Image;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Attribute\Setter;
use Spiral\Filters\Model\Filter;
use Spiral\Filters\Model\FilterDefinitionInterface;
use Spiral\Filters\Model\HasFilterDefinition;
use Spiral\Validator\FilterDefinition;
use Spiral\Writeaway\Database\Image;

class ImageRequest extends Filter implements HasFilterDefinition
{
    #[Post]
    #[Setter(filter: 'intval')]
    public readonly int $id;

    public function filterDefinition(): FilterDefinitionInterface
    {
        return new FilterDefinition([
            'id' => [
                'required',
                ['entity:exists', Image::class, 'error' => '[[Image not exists.]]']
            ]
        ]);
    }
}
