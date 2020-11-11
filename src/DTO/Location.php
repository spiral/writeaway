<?php

declare(strict_types=1);

namespace Spiral\WriteAway\DTO;

class Location
{
    public string $namespace;
    public string $view;
    public bool $filled;

    public function __construct(string $namespace, string $view)
    {
        $this->namespace = $namespace;
        $this->view = $view;
        $this->filled = $namespace && $view;
    }
}
