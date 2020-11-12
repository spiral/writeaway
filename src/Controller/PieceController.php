<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Controller;

use Spiral\Http\Exception\ClientException\ServerErrorException;
use Spiral\Logger\Traits\LoggerTrait;
use Spiral\WriteAway\Request\Piece\BulkRequest;
use Spiral\WriteAway\Request\Piece\DataRequest;
use Spiral\WriteAway\Request\Piece\LocationRequest;
use Spiral\WriteAway\Request\Piece\PieceRequest;
use Spiral\WriteAway\Service\Pieces;

class PieceController
{
    use LoggerTrait;

    private Pieces $pieces;

    public function __construct(Pieces $pieces)
    {
        $this->pieces = $pieces;
    }

    public function save(PieceRequest $pieceRequest, DataRequest $dataRequest, LocationRequest $locationRequest): array
    {
        $piece = $this->pieces->get($pieceRequest->id());
        try {
            $this->pieces->save($piece, $dataRequest->data, $locationRequest->location());
        } catch (\Throwable $exception) {
            $this->getLogger('default')->error('Piece update failed', compact('exception'));
            throw new ServerErrorException('Piece update failed', $exception);
        }

        return [
            'status' => 200,
            'data'   => $piece->pack()
        ];
    }

    public function get(PieceRequest $request): array
    {
        $piece = $this->pieces->get($request->id());

        return [
            'status' => 200,
            'data'   => $piece->pack()
        ];
    }

    public function bulk(BulkRequest $request): array
    {
        return [
            'status' => 200,
            'data'   => $this->pieces->getBulkList(...$request->ids())
        ];
    }
}
