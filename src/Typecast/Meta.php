<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Typecast;

use Spiral\Writeaway\DTO;

class Meta extends Json
{
    public static function fromDTO(DTO\Meta $meta): self
    {
        return new self($meta->toArray());
    }
}
