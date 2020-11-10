<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spiral\WriteAway\Mapper\TimestampsMapper;
use Spiral\WriteAway\Mapper\Traits\Timestamps;
use Spiral\WriteAway\Repository\PieceRepository;
use Spiral\WriteAway\Typecast\Json;

/**
 * @Cycle\Entity(table="pieces", mapper=TimestampsMapper::class, repository=PieceRepository::class)
 * @Cycle\Table(indexes={@Cycle\Table\Index(columns={"code"}, unique=true)})
 */
class Piece
{
    use Timestamps;

    /**
     * @Cycle\Column(type="string", primary=true)
     */
    public string $id;
    /**
     * @Cycle\Column(type="string")
     */
    public string $type;
    /**
     * @Cycle\Column(type="longText", typecast=Json::class)
     */
    public Json $data;
    /**
     * @Cycle\Relation\HasMany(target=Piece\Location::class)
     * @var Collection|Piece\Location
     */
    public Collection $locations;

    public function __construct(string $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
        $this->data = new Json();
        $this->locations = new ArrayCollection();
    }

    public function pack(): array
    {
        return [
            'id'   => $this->id,
            'type' => $this->type,
            'data' => $this->data->toArray()
        ];
    }

    public function hasLocation(string $namespace, string $view): bool
    {
        foreach ($this->locations as $location) {
            if ($location->namespace === $namespace && $location->view === $view) {
                return true;
            }
        }

        return false;
    }
}
