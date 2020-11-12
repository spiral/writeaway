<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Request\Piece;

use Spiral\Filters\Filter;
use Spiral\Writeaway\DTO\PieceID;

class BulkRequest extends Filter
{
    protected const SCHEMA = [
        'pieces' => [PieceRequest::class]
    ];

    /**
     * @return PieceID[]
     */
    public function ids(): array
    {
        return array_map(
            static fn (PieceRequest $request): PieceID => $request->id(),
            $this->getField('pieces')
        );
    }
}
