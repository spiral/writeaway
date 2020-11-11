<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service;

use Cycle\ORM\TransactionInterface;
use Spiral\WriteAway\Database\Piece;
use Spiral\WriteAway\DTO;
use Spiral\WriteAway\Model\PieceID;
use Spiral\WriteAway\Repository\PieceRepository;
use Spiral\WriteAway\Typecast\Json;

class Pieces
{
    private TransactionInterface $transaction;
    private PieceRepository $pieceRepository;

    public function __construct(TransactionInterface $transaction, PieceRepository $pieceRepository)
    {
        $this->transaction = $transaction;
        $this->pieceRepository = $pieceRepository;
    }

    public function getBulkList(PieceID ...$ids): array
    {
        return array_map(
            static fn (Piece $piece): array => $piece->pack(),
            $this->pieceRepository->findByIDs($ids)->fetchAll()
        );
    }

    public function get(PieceID $id): Piece
    {
        $piece = $this->pieceRepository->findByPK($id->id());
        if (!$piece instanceof Piece) {
            //todo add piece meta
            $piece = new Piece($id);
        }

        return $piece;
    }

    /**
     * @param Piece    $piece
     * @param array    $data
     * @param DTO\Location $location
     * @throws \Throwable
     */
    public function save(Piece $piece, array $data, DTO\Location $location): void
    {
        $piece->data = new Json($data);
        if ($location->filled && !$piece->hasLocation($location)) {
            $piece->locations->add(Piece\Location::createFromDTO($location));
        }

        $this->transaction->persist($piece);
        $this->transaction->run();
    }
}
