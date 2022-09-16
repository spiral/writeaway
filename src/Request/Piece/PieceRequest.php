<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;
use Spiral\Filters\Model\FilterDefinitionInterface;
use Spiral\Filters\Model\HasFilterDefinition;
use Spiral\Validator\FilterDefinition;
use Spiral\Writeaway\DTO\PieceID;

class PieceRequest extends Filter implements HasFilterDefinition
{
    #[Post(key: 'id')]
    public string $name;

    #[Post]
    public string $type;

    public function id(): PieceID
    {
        return PieceID::create($this->type, $this->name);
    }

    public function filterDefinition(): FilterDefinitionInterface
    {
        return new FilterDefinition([
            'name' => [
                'notEmpty',
                ['is_string', 'error' => '[[ID should be a string]]']
            ],
            'type' => [
                'notEmpty',
                ['is_string', 'error' => '[[Type should be a string]]']
            ],
        ]);
    }
}
