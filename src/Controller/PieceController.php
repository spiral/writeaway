<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Controller;

use Spiral\Http\Exception\ClientException\ServerErrorException;
use Spiral\Logger\Traits\LoggerTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Translator\Traits\TranslatorTrait;
use Spiral\WriteAway\Request\Piece\LocationRequest;
use Spiral\WriteAway\Request\Piece\PieceRequest;
use Spiral\WriteAway\Service\Pieces;

class PieceController
{
    use LoggerTrait;
    use TranslatorTrait;

    private Pieces $pieces;

    public function __construct(Pieces $pieces)
    {
        $this->pieces = $pieces;
    }

    /**
     * @Route(name="writeAway:pieces:save", group="writeAway", methods="POST", route="pieces/save")
     * @param PieceRequest    $pieceRequest
     * @param LocationRequest $locationRequest
     * @return array
     */
    public function save(PieceRequest $pieceRequest, LocationRequest $locationRequest): array
    {
        $piece = $this->pieces->get($pieceRequest->id, $pieceRequest->type);
        try {
            $this->pieces->save($piece, $pieceRequest->data, $locationRequest->namespace, $locationRequest->view);
        } catch (\Throwable $exception) {
            $this->getLogger('default')->error('Piece update failed', compact('exception'));
            throw new ServerErrorException('Piece update failed', $exception);
        }

        return [
            'status' => 200,
            'data'   => $piece->pack()
        ];
    }

    /**
     * @Route(name="writeAway:pieces:get", group="writeAway", methods="GET", route="pieces/get")
     * @param PieceRequest $request
     * @return array
     */
    public function get(PieceRequest $request): array
    {
        $piece = $this->pieces->get($request->id, $request->type);

        return [
            'status' => 200,
            'data'   => $piece->pack()
        ];
    }
}
