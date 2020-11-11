<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Model;

class PieceID
{
    protected const SEPARATOR = ':';

    public string $name;
    public string $type;

    public static function create(string $type, string $name): self
    {
        $id = new self();
        $id->type = $type;
        $id->name = $name;
        return $id;
    }

    public function id(): string
    {
        return $this->type . static::SEPARATOR . $this->name;
    }
}
