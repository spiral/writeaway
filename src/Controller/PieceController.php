<?php

declare(strict_types=1);

namespace Spiral\Keeper\Controller;

use Spiral\Http\Exception\ClientException\ServerErrorException;
use Spiral\Logger\Traits\LoggerTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Translator\Traits\TranslatorTrait;
use Spiral\WriteAway\Database\Piece;
use Spiral\WriteAway\Repository\PieceRepository;
use Spiral\WriteAway\Request\PieceRequest;
use Spiral\WriteAway\Service\Pieces;

class PieceController
{
    use LoggerTrait;
    use TranslatorTrait;

    private Pieces $pieces;
    private PieceRepository $pieceRepository;

    public function __construct(Pieces $pieces, PieceRepository $pieceRepository)
    {
        $this->pieces = $pieces;
        $this->pieceRepository = $pieceRepository;
    }

    /**
     * @Route(name="writeAway:pieces:save", group="writeAway", methods="POST", route="pieces/save")
     * @param PieceRequest $request
     * @return array
     */
    public function save(PieceRequest $request): array
    {
        $piece = $this->pieceRepository->findByCode($request->code);
        if (!$piece instanceof Piece) {
            return [
                'status' => 400,
                'error'  => $this->say('Unable to find requested piece.')
            ];
        }

        try {
            $this->pieces->savePiece($piece, $request->getContent());
        } catch (\Throwable $exception) {
            $this->getLogger('default')->error('Piece update failed', compact('exception'));
            throw new ServerErrorException('Piece update failed', $exception);
        }

        return [
            'status' => 200,
            'id'     => $piece->id,
        ];
    }

    /**
     * @Route(name="writeAway:pieces:get", group="writeAway", methods="GET", route="pieces/get")
     * @param PieceRequest $request
     * @return array
     */
    public function get(PieceRequest $request): array
    {
        $piece = $this->pieceRepository->findByCode($request->code);
        if (!$piece instanceof Piece) {
            return [
                'status' => 400,
                'error'  => $this->say('Unable to find requested piece.')
            ];
        }

        return [
            'status' => 200,
            'piece'  => $piece->pack()
        ];
    }
}
