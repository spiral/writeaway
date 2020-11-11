<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Model;

class PieceID
{
    protected const SEPARATOR = ':';

    public string $code;
    public string $type;

    public static function create(string $type, string $code): self
    {
        $id = new self();
        $id->type = $type;
        $id->code = $code;
        return $id;
    }

    public function id(): string
    {
        return $this->type . static::SEPARATOR . $this->code;
    }
}
