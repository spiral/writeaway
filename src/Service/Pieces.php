<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service;

use Cycle\ORM\TransactionInterface;
use Spiral\WriteAway\Database\Meta;
use Spiral\WriteAway\Database\Piece;
use Spiral\WriteAway\Repository\MetaRepository;
use Spiral\WriteAway\Repository\PieceRepository;

class Pieces
{
    private TransactionInterface $transaction;
    private PieceRepository $pieceRepository;
    private MetaRepository $metaRepository;

    public function __construct(
        TransactionInterface $transaction,
        PieceRepository $pieceRepository,
        MetaRepository $metaRepository
    ) {
        $this->transaction = $transaction;
        $this->pieceRepository = $pieceRepository;
        $this->metaRepository = $metaRepository;
    }

    /**
     * @param Meta   $meta
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param string $html
     * @throws \Throwable
     */
    public function saveMeta(Meta $meta, string $title, string $description, string $keywords, string $html): void
    {
        $meta->title = $title;
        $meta->description = $description;
        $meta->keywords = $keywords;
        $meta->html = $html;

        $this->transaction->persist($meta);
        $this->transaction->run();
    }

    /**
     * @param string $code
     * @param string $defaultContent
     * @param string $namespace
     * @param string $view
     * @return Piece
     * @throws \Throwable
     */
    public function getPiece(
        string $code,
        string $defaultContent = '',
        string $namespace = '',
        string $view = ''
    ): Piece {
        $piece = $this->pieceRepository->findByCode($code);
        if (!$piece instanceof Piece) {
            $piece = new Piece();
            $piece->code = $code;
            $this->savePiece($piece, $defaultContent);
        }

        return $this->ensureLocation($piece, $namespace, $view);
    }

    /**
     * @param Piece  $piece
     * @param string $content
     * @throws \Throwable
     */
    public function savePiece(Piece $piece, string $content): void
    {
        $piece->content = $content;
        $this->transaction->persist($piece);
        $this->transaction->run();
    }

    /**
     * @param string $namespace
     * @param string $view
     * @param string $code
     * @param array  $defaults
     * @return Meta
     * @throws \Throwable
     */
    public function getMeta(
        string $namespace,
        string $view,
        string $code,
        array $defaults
    ): Meta {
        $meta = $this->metaRepository->findMeta($namespace, $view, $code);
        if (!$meta instanceof Meta) {
            $meta = new Meta();
            $meta->namespace = $namespace;
            $meta->view = $view;
            $meta->code = $code;

            $this->saveMeta(
                $meta,
                $defaults['title'] ?? '',
                $defaults['description'] ?? '',
                $defaults['keywords'] ?? '',
                $defaults['html'] ?? ''
            );
        }

        return $meta;
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

            $this->transaction->persist($piece);
            $this->transaction->run();
        }

        return $piece;
    }
}
