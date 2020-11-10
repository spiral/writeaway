<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database\Piece;

use Cycle\Annotated\Annotation as Cycle;

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
}
