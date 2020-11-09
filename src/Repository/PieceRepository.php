<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Repository;

use Cycle\ORM\Select\Repository;
use Spiral\WriteAway\Database\Piece;

class PieceRepository extends Repository
{
    public function findByCode(string $code): ?Piece
    {
        /** @var Piece|null $piece */
        $piece = $this->findOne(compact('code'));
        return $piece;
    }
}
