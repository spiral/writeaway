<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Spiral\Storage\Storage;
use Spiral\Writeaway\Mapper\TimestampsMapper;
use Spiral\Writeaway\Mapper\Traits\Timestamps;
use Spiral\Writeaway\Repository\ImageRepository;

/**
 * @Cycle\Entity(table="images", mapper=TimestampsMapper::class, repository=ImageRepository::class)
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

    public function pack(?Storage $storage = null): array
    {
        return [
            'id'           => $this->id,
            'src'          => isset($storage) ? (string) $storage->file($this->original)->toUri() : $this->original,
            'thumbnailSrc' => isset($storage) ? (string) $storage->file($this->thumbnail)->toUri() : $this->thumbnail,
            'width'        => $this->width,
            'height'       => $this->height,
        ];
    }

    public function calcRatio(): void
    {
        $this->ratio = $this->height ? $this->width / $this->height : 0;
    }
}
