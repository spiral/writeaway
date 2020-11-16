<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spiral\Writeaway\DTO;
use Spiral\Writeaway\Mapper\TimestampsMapper;
use Spiral\Writeaway\Mapper\Traits\Timestamps;
use Spiral\Writeaway\Repository\PieceRepository;
use Spiral\Writeaway\Typecast;

/**
 * @Cycle\Entity(table="pieces", mapper=TimestampsMapper::class, repository=PieceRepository::class)
 * @Cycle\Table(indexes={@Cycle\Table\Index(columns={"name", "type"}, unique=true)})
 */
class Piece
{
    use Timestamps;

    /**
     * @Cycle\Column(type="longText", typecast=Typecast\Json::class)
     */
    public Typecast\Json $data;
    /**
     * @Cycle\Column(type="longText", typecast=Typecast\Meta::class)
     */
    public Typecast\Meta $meta;
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
        $this->data = new Typecast\Json();
        $this->meta = new Typecast\Meta();
        $this->locations = new ArrayCollection();
    }

    public function pack(): array
    {
        return [
            'id'   => $this->name,
            'type' => $this->type,
            'data' => $this->data->toArray(),
            'meta' => $this->meta->toArray()
        ];
    }

    public function updateData(array $data): bool
    {
        if ($this->data->toArray() === $data) {
            return false;
        }

        $this->data = new Typecast\Json($data);
        return true;
    }

    public function updateMeta(DTO\Meta $meta): bool
    {
        if ($this->meta->toArray() === Typecast\Meta::fromDTO($meta)->toArray()) {
            return false;
        }

        $this->meta = Typecast\Meta::fromDTO($meta);
        return true;
    }

    public function addLocation(DTO\Location $location): bool
    {
        if ($location->filled && !$this->hasLocation($location)) {
            $this->locations->add(Piece\Location::createFromDTO($location));
            return true;
        }
        return false;
    }

    private function hasLocation(DTO\Location $locationDTO): bool
    {
        foreach ($this->locations as $location) {
            if ($location->isSame($locationDTO)) {
                return true;
            }
        }

        return false;
    }
}
