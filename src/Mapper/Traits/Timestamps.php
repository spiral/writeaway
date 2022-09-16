<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Mapper\Traits;

use Cycle\Annotated\Annotation as Cycle;
use DateTimeImmutable;

trait Timestamps
{
    #[Cycle\Column(type: 'datetime', nullable: true)]
    private ?DateTimeImmutable $time_created = null;

    #[Cycle\Column(type: 'datetime', nullable: true)]
    private ?DateTimeImmutable $time_updated = null;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->time_created;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->time_updated;
    }
}
