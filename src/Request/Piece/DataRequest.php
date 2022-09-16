<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;
use Spiral\Filters\Model\FilterDefinitionInterface;
use Spiral\Filters\Model\HasFilterDefinition;
use Spiral\Validator\FilterDefinition;

class DataRequest extends Filter implements HasFilterDefinition
{
    #[Post]
    public array $data = [];

    public function filterDefinition(): FilterDefinitionInterface
    {
        return new FilterDefinition([
            'data' => [
                ['is_array', 'error' => '[[Data should be an array.]]', 'if' => ['withAll' => 'data']]
            ]
        ]);
    }
}
