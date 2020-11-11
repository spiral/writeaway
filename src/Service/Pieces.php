<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service;

use Cycle\ORM\TransactionInterface;
use Spiral\WriteAway\Database\Piece;
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
     * @param Piece $piece
     * @param array $data
     * @throws \Throwable
     */
    public function save(Piece $piece, array $data): void
    {
        $piece->data = new Json($data);
        //$this->ensureLocation($piece, $namespace, $view);

        $this->transaction->persist($piece);
        $this->transaction->run();
    }

    /**
     * @param Piece  $piece
     * @param string $namespace
     * @param string $view
     * @return Piece
     * @throws \Throwable
     */
    private function ensureLocation(Piece $piece, string $namespace, string $view): Piece
    {
        if (!$piece->hasLocation($namespace, $view)) {
            $location = new Piece\Location();
            $location->namespace = $namespace;
            $location->view = $view;

            $piece->locations->add($location);
        }

        return $piece;
    }
}
