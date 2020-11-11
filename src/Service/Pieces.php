<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service;

use Cycle\ORM\TransactionInterface;
use Spiral\WriteAway\Database\Piece;
use Spiral\WriteAway\DTO;
use Spiral\WriteAway\Repository\PieceRepository;
use Spiral\WriteAway\Service\Meta\ProviderInterface;
use Spiral\WriteAway\Typecast;

class Pieces
{
    private TransactionInterface $transaction;
    private PieceRepository $pieceRepository;
    private ProviderInterface $metaProvider;

    public function __construct(
        TransactionInterface $transaction,
        PieceRepository $pieceRepository,
        ProviderInterface $metaProvider
    ) {
        $this->transaction = $transaction;
        $this->pieceRepository = $pieceRepository;
        $this->metaProvider = $metaProvider;
    }

    public function getBulkList(DTO\PieceID ...$ids): array
    {
        return array_map(
            static fn (Piece $piece): array => $piece->pack(),
            $this->pieceRepository->findByIDs($ids)->fetchAll()
        );
    }

    public function get(DTO\PieceID $id): Piece
    {
        $piece = $this->pieceRepository->findByPK($id->id());
        if (!$piece instanceof Piece) {
            $piece = new Piece($id);
        }

        return $piece;
    }

    /**
     * @param Piece        $piece
     * @param array        $data
     * @param DTO\Location $location
     * @throws \Throwable
     */
    public function save(Piece $piece, array $data, DTO\Location $location): void
    {
        $piece->data = new Typecast\Json($data);
        $piece->meta = new Typecast\Meta($this->metaProvider->provide());
        if ($location->filled && !$piece->hasLocation($location)) {
            $piece->locations->add(Piece\Location::createFromDTO($location));
        }

        $this->transaction->persist($piece);
        $this->transaction->run();
    }
}
