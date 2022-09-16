<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Attribute\Setter;
use Spiral\Filters\Model\Filter;
use Spiral\Filters\Model\FilterDefinitionInterface;
use Spiral\Filters\Model\HasFilterDefinition;
use Spiral\Validator\FilterDefinition;
use Spiral\Writeaway\DTO\Location;

class LocationRequest extends Filter implements HasFilterDefinition
{
    #[Post]
    #[Setter(filter: 'strval')]
    public string $namespace = '';

    #[Post]
    #[Setter(filter: 'strval')]
    public string $view = '';

    public function location(): Location
    {
        return new Location($this->namespace, $this->view);
    }

    public function filterDefinition(): FilterDefinitionInterface
    {
        return new FilterDefinition([
            'namespace' => [
                ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'namespace']]
            ],
            'view' => [
                ['is_string', 'error' => '[[Should be a string.]]', 'if' => ['withAll' => 'view']]
            ]
        ]);
    }
}
