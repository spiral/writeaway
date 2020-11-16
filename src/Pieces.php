<?php

declare(strict_types=1);

namespace Spiral\Writeaway;

use Spiral\Writeaway\Database\Piece;
use Spiral\Writeaway\DTO\Location;
use Spiral\Writeaway\DTO\PieceID;
use Spiral\Writeaway\Repository\PieceRepository;
use Spiral\Writeaway\Service\Pieces as Service;

class Pieces
{
    private Service $pieces;
    private PieceRepository $pieceRepository;

    public function __construct(Service $service, PieceRepository $pieceRepository)
    {
        $this->pieces = $service;
        $this->pieceRepository = $pieceRepository;
    }

    /**
     * @param string $type
     * @param string $name
     * @param array  $defaults
     * @param string $namespace
     * @param string $view
     * @return array
     * @throws \Throwable
     */
    public function get(
        string $type,
        string $name,
        array $defaults = [],
        string $namespace = '',
        string $view = ''
    ): array {
        $id = PieceID::create($type, $name);
        $location = new Location($namespace, $view);

        $piece = $this->pieceRepository->findByPK($id->id());
        if (!$piece instanceof Piece) {
            $piece = new Piece($id);
            $this->pieces->save($piece, $defaults, $location);
            return $defaults;
        }

        $this->pieces->saveMeta($piece, $location);
        return $piece->data->toArray();
    }
}
