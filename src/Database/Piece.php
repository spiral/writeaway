<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spiral\WriteAway\Mapper\TimestampsMapper;
use Spiral\WriteAway\Mapper\Traits\Timestamps;
use Spiral\WriteAway\DTO;
use Spiral\WriteAway\Repository\PieceRepository;
use Spiral\WriteAway\Typecast\Json;

/**
 * @Cycle\Entity(table="pieces", mapper=TimestampsMapper::class, repository=PieceRepository::class)
 * @Cycle\Table(indexes={@Cycle\Table\Index(columns={"name", "type"}, unique=true)})
 */
class Piece
{
    use Timestamps;

    /**
     * @Cycle\Column(type="longText", typecast=Json::class)
     */
    public Json $data;
    /**
     * @Cycle\Relation\HasMany(target=Piece\Location::class)
     * @var Collection|Piece\Location[]
     */
    public Collection $locations;
    /**
     * @Cycle\Column(type="string", primary=true)
     */
    protected string $id;
    /**
     * @Cycle\Column(type="string")
     */
    protected string $name;
    /**
     * @Cycle\Column(type="string")
     */
    protected string $type;

    public function __construct(DTO\PieceID $id)
    {
        $this->id = $id->id();
        $this->name = $id->name;
        $this->type = $id->type;
        $this->data = new Json();
        $this->locations = new ArrayCollection();
    }

    public function pack(): array
    {
        return [
            'id'   => $this->name,
            'type' => $this->type,
            'data' => $this->data->toArray()
        ];
    }

    public function hasLocation(DTO\Location $locationDTO): bool
    {
        foreach ($this->locations as $location) {
            if ($location->isSame($locationDTO)) {
                return true;
            }
        }

        return false;
    }
}
