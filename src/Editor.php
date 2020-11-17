<?php

declare(strict_types=1);

namespace Spiral\Writeaway;

use Spiral\Security\GuardInterface;
use Spiral\Writeaway\Config\WriteawayConfig;
use Spiral\Writeaway\Database\Piece;
use Spiral\Writeaway\DTO\Location;
use Spiral\Writeaway\DTO\PieceID;
use Spiral\Writeaway\Repository\PieceRepository;
use Spiral\Writeaway\Service\Pieces;

class Editor
{
    private Pieces $pieces;
    private PieceRepository $repository;
    private GuardInterface $guard;
    private WriteawayConfig $config;
    private MetaProviderInterface $metaProvider;

    public function __construct(
        Pieces $pieces,
        PieceRepository $repository,
        GuardInterface $guard,
        WriteawayConfig $config,
        MetaProviderInterface $metaProvider
    ) {
        $this->pieces = $pieces;
        $this->repository = $repository;
        $this->guard = $guard;
        $this->config = $config;
        $this->metaProvider = $metaProvider;
    }

    public function allows(string $type = null, string $name = null): bool
    {
        return $this->guard->allows($this->config->editPermission(), compact('type', 'name'));
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
    public function getPiece(
        string $type,
        string $name,
        array $defaults = [],
        string $namespace = '',
        string $view = ''
    ): array {
        $id = PieceID::create($type, $name);
        $location = new Location($namespace, $view);

        $piece = $this->repository->findByPK($id->id());
        if (!$piece instanceof Piece) {
            $piece = new Piece($id);
            $this->pieces->save($piece, $defaults, $location);
            return $defaults;
        }

        $this->pieces->saveMeta($piece, $location);
        return $piece->data->toArray();
    }

    public function getMeta(): array
    {
        return $this->metaProvider->provide()->toArray();
    }
}
