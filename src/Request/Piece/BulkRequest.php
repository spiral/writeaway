<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Request\Piece;

use Spiral\Filters\Filter;
use Spiral\WriteAway\Model\PieceID;

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
            $names = $this->getField('pieces')
        );
    }
}
