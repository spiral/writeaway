<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Database;

use Cycle\Annotated\Annotation as Cycle;
use Spiral\WriteAway\Mapper\TimestampsMapper;
use Spiral\WriteAway\Mapper\Traits\Timestamps;
use Spiral\WriteAway\Repository\MetaRepository;

/**
 * @Cycle\Entity(table="metas", mapper=TimestampsMapper::class, repository=MetaRepository::class)
 * @Cycle\Table(indexes={@Cycle\Table\Index(columns={"namespace", "view", "code"}, unique=true)})
 */
class Meta
{
    use Timestamps;

    /**
     * @Cycle\Column(type="primary")
     */
    public ?int $id = null;
    /**
     * @Cycle\Column(type="string")
     */
    public string $namespace;
    /**
     * @Cycle\Column(type="string")
     */
    public string $view;
    /**
     * @Cycle\Column(type="string")
     */
    public string $code;
    /**
     * @Cycle\Column(type="string")
     */
    public string $title;
    /**
     * @Cycle\Column(type="text", default="")
     */
    public string $description;
    /**
     * @Cycle\Column(type="text", default="")
     */
    public string $keywords;
    /**
     * @Cycle\Column(type="text", default="")
     */
    public string $html;

    public function pack(): array
    {
        return [
            'id'          => $this->id,
            'html'        => $this->html,
            'title'       => $this->title,
            'description' => $this->description,
            'keywords'    => $this->keywords,
        ];
    }
}
