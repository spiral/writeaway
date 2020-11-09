<?php

declare(strict_types=1);

namespace Spiral\Debug;

namespace Spiral\Debug;

final class Benchmark
{
    private int $start;
    private ?int $finish = null;
    private string $caller;
    private string $event;
    private $context;

    /**
     * @param string $caller
     * @param string $event
     * @param mixed  $context
     */
    public function __construct(string $caller, string $event, $context)
    {
        $this->start = (int)microtime(true);
        $this->caller = $caller;
        $this->event = $event;
        $this->context = $context;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getFinish(): ?int
    {
        return $this->finish;
    }

    public function getCaller(): string
    {
        return $this->caller;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function isComplete(): bool
    {
        return !empty($this->finish);
    }

    public function complete(): int
    {
        $this->finish = (int)microtime(true);

        return $this->getElapsed();
    }

    public function getElapsed(): ?int
    {
        if (!$this->isComplete()) {
            return null;
        }

        return $this->finish - $this->start;
    }
}
