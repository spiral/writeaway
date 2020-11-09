<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Spiral\WriteAway\Mapper\Traits\Timestamps;
use Spiral\WriteAway\Repository\ImageRepository;

/**
 * @Cycle\Entity(table="images", mapper="App\Mapper\TimestampsMapper", repository=ImageRepository::class)
 */
class Image
{
    use Timestamps;

    /**
     * @Cycle\Column(type="primary")
     */
    public ?int $id = null;
    /**
     * @Cycle\Column(type="int")
     */
    public int $width;
    /**
     * @Cycle\Column(type="int")
     */
    public int $height;
    /**
     * @Cycle\Column(type="int")
     */
    public int $size;
    /**
     * @Cycle\Column(type="float")
     */
    public float $ratio;
    /**
     * @Cycle\Column(type="text")
     */
    public string $thumbnail;
    /**
     * @Cycle\Column(type="text")
     */
    public string $original;

    public function pack(): array
    {
        return [
            'id'           => $this->id,
            'url'          => $this->original,
            'thumbnailUrl' => $this->thumbnail,
            'width'        => $this->width,
            'height'       => $this->height,
        ];
    }
}
