<?php

declare(strict_types=1);

namespace Spiral\WriteAway\DTO;

use Spiral\WriteAway\Helper\DateHelper;

class Meta
{
    private string $id;
    private string $label;
    private \DateTimeImmutable $time;

    public function __construct(string $id, string $label, \DateTimeImmutable $time = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->time = $time ?? DateHelper::immutable();
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'label' => $this->label,
            'time'  => $this->time->format('c'),
        ];
    }
}
