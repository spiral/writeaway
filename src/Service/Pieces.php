<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Service;

use Cycle\ORM\EntityManagerInterface;
use Spiral\Writeaway\Database\Piece;
use Spiral\Writeaway\DTO;
use Spiral\Writeaway\MetaProviderInterface;
use Spiral\Writeaway\Repository\PieceRepository;

/**
 * @internal
 */
class Pieces
{
    public function __construct(
        private EntityManagerInterface $em,
        private PieceRepository $pieceRepository,
        private MetaProviderInterface $metaProvider
    ) {
    }

    public function getBulkList(DTO\PieceID ...$ids): array
    {
        return array_map(
            static fn (Piece $piece): array => $piece->pack(),
            $this->pieceRepository->findByIDs(
                array_map(
                    static fn (DTO\PieceID $id): string => $id->id(),
                    $ids
                )
            )->fetchAll()
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
     * @throws \Throwable
     */
    public function save(Piece $piece, array $data, DTO\Location $location): void
    {
        $updatedData = $piece->updateData($data);
        $updatedMeta = $piece->updateMeta($this->metaProvider->provide());
        $updatedLocation = $piece->addLocation($location);

        if ($updatedData || $updatedMeta || $updatedLocation) {
            $this->em->persist($piece);
            $this->em->run();
        }
    }

    /**
     * @throws \Throwable
     */
    public function saveMeta(Piece $piece, DTO\Location $location): void
    {
        $updatedMeta = $piece->updateMeta($this->metaProvider->provide());
        $updatedLocation = $piece->addLocation($location);

        if ($updatedMeta || $updatedLocation) {
            $this->em->persist($piece);
            $this->em->run();
        }
    }
}
