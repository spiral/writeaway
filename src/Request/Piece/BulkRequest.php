<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Attribute\NestedArray;
use Spiral\Filters\Attribute\NestedFilter;
use Spiral\Filters\Model\Filter;
use Spiral\Filters\Model\FilterDefinitionInterface;
use Spiral\Filters\Model\HasFilterDefinition;
use Spiral\Validator\FilterDefinition;
use Spiral\Writeaway\DTO\PieceID;

class BulkRequest extends Filter implements HasFilterDefinition
{
    #[NestedFilter(class: PieceRequest::class)]
    #[NestedArray(class: PieceRequest::class, input: new Post('pieces'))]
    public array $pieces = [];

    /**
     * @return PieceID[]
     */
    public function ids(): array
    {
        return array_map(
            static fn (PieceRequest $request): PieceID => $request->id(),
            $this->pieces
        );
    }

    public function filterDefinition(): FilterDefinitionInterface
    {
        return new FilterDefinition([
            'pieces' => ['required']
        ]);
    }
}
