<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Repository;

use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;
use Spiral\Database\Injection\Parameter;
use Spiral\Writeaway\Database\Piece;

class PieceRepository extends Repository
{
    /**
     * @param array $ids
     * @return Select|Piece[]
     */
    public function findByIDs(array $ids): Select
    {
        return $this->select()->where(
            [
                'id' => empty($ids)
                    ? ['is' => null]
                    : ['IN' => new Parameter($ids)]
            ]
        );
    }
}
