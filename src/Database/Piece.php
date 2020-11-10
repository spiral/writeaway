<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spiral\WriteAway\Mapper\TimestampsMapper;
use Spiral\WriteAway\Mapper\Traits\Timestamps;
use Spiral\WriteAway\Repository\PieceRepository;

/**
 * @Cycle\Entity(table="pieces", mapper=TimestampsMapper::class, repository=PieceRepository::class)
 * @Cycle\Table(indexes={@Cycle\Table\Index(columns={"code"}, unique=true)})
 */
class Piece
{
    use Timestamps;

    /**
     * @Cycle\Column(type="primary")
     */
    public ?int $id = null;
    /**
     * @Cycle\Column(type="string", default="")
     */
    public string $code;
    /**
     * @Cycle\Column(type="text", default="")
     */
    public string $content;
    /**
     * @Cycle\Relation\HasMany(target=Piece\Location::class)
     * @var Collection|Piece\Location
     */
    public Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    public function pack(): array
    {
        return [
            'id'   => $this->id,
            'code' => $this->code,
            'html' => (string)$this->content,
        ];
    }

    public function hasLocation(string $namespace, string $view): bool
    {
        foreach ($this->locations as $location) {
            if ($location->view === $view && $location->namespace === $namespace) {
                return true;
            }
        }

        return false;
    }
}
