<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Typecast;

use JsonException;
use Spiral\Database\DatabaseInterface;

class Json
{
    private array $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function __toString(): string
    {
        return (string)json_encode($this->values, JSON_THROW_ON_ERROR);
    }

    /**
     * @param mixed             $value
     * @param DatabaseInterface $db
     * @return static
     * @throws JsonException
     */
    public static function typecast($value, DatabaseInterface $db): self
    {
        return self::fromString($value);
    }

    /**
     * @param string $value
     * @return static
     * @throws JsonException
     */
    public static function fromString(string $value): self
    {
        $decoded = $value ? (array)json_decode($value, true, 512, JSON_THROW_ON_ERROR) : [];
        return new static($decoded);
    }

    public function toArray(): array
    {
        return $this->values;
    }
}
