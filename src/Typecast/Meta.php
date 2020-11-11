<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Typecast;

use Spiral\WriteAway\DTO;

class Meta extends Json
{
    public function __construct(DTO\Meta $meta = null)
    {
        parent::__construct($meta ? $meta->toArray() : []);
    }
}
