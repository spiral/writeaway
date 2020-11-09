<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database\Piece;

use Cycle\Annotated\Annotation as Cycle;
use Spiral\WriteAway\Database\Piece;

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
     * @Cycle\Column(type="string(255)", default="")
     */
    public string $view;
    /**
     * @Cycle\Column(type="string(255)", default="")
     */
    public string $namespace;
    /**
     * @Cycle\Relation\BelongsTo(
     *     target="App\Database\Spiral\Piece",
     *     cascade=null,
     *     nullable=false,
     *     innerKey="piece_id",
     *     outerKey="id",
     *     fkCreate=null,
     *     fkAction=null,
     *     indexCreate=null,
     *     load=null
     * )
     */
    public Piece $piece;
}
