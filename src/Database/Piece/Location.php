<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database\Piece;

use Cycle\Annotated\Annotation as Cycle;
use Spiral\WriteAway\DTO;

/**
 * @Cycle\Entity(table="piece_locations")
 */
class Location
{
    /**
     * @Cycle\Column(type="primary")
     */
    public ?int $id = null;
    /**
     * @Cycle\Column(type="string(255)")
     */
    public string $namespace;
    /**
     * @Cycle\Column(type="string(255)")
     */
    public string $view;

    public function isSame(DTO\Location $location): bool
    {
        return $this->namespace === $location->namespace && $this->view === $location->view;
    }

    public static function createFromDTO(DTO\Location $location): self
    {
        $self = new self();
        $self->namespace = $location->namespace;
        $self->view = $location->view;
        return $self;
    }
}
