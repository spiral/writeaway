<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Validation\Checker;

use Spiral\Validation\AbstractChecker;
use Spiral\WriteAway\Database\Piece;
use Spiral\WriteAway\Repository\PieceRepository;

class PieceChecker extends AbstractChecker
{
    public const MESSAGES = [
        'id' => '[[ID is already taken by that type.]]'
    ];

    private PieceRepository $pieceRepository;

    public function __construct(PieceRepository $pieceRepository)
    {
        $this->pieceRepository = $pieceRepository;
    }

    public function id(string $id, string $typeField): bool
    {
        $piece = $this->pieceRepository->findByPK($id);
        return !$piece instanceof Piece || $piece->type === $this->getValidator()->getValue($typeField);
    }
}
